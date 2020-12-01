import * as sideQuestResultApi from '../../api/sideQuestResultApi';
import CombatEvent from "../../models/CombatEvent";
import CombatSquad from "../../models/CombatSquad";
import SideQuestGroup from "../../models/SideQuestGroup";
export default {

    state: {
        sideQuestResult: null,
        sideQuestMoment: 0,
        sideQuestCombatSquad: null,
        sideQuestEnemyGroup: null,
        sideQuestEvents: [],
        triggeredSideQuestEvents: [],
        currentSideQuestEvents: [],
        sideQuestReplaySpeed: 1000,
        sideQuestReplayPaused: true,
        sqReplaySquadTotalHealth: 0,
        sqReplayEnemyTotalHealth: 0
    },

    getters: {
        _sideQuestResult(state) {
            return state.sideQuestResult;
        },
        _sideQuestCombatSquad(state) {
            return state.sideQuestCombatSquad;
        },
        _sideQuestEnemyGroup(state) {
            return state.sideQuestEnemyGroup;
        },
        _sqReplaySquadTotalHealth(state) {
            return state.sqReplaySquadTotalHealth;
        },
        _sqReplayEnemyTotalHealth(state) {
            return state.sqReplayEnemyTotalHealth;
        },
        _sideQuestMoment(state) {
            return state.sideQuestMoment;
        },
        _triggeredSideQuestEvents(state) {
            return state.triggeredSideQuestEvents;
        },
        _sideQuestReplayPaused(state) {
            return state.sideQuestReplayPaused;
        },
        _currentSideQuestEvents(state) {
            return state.currentSideQuestEvents;
        }
    },
    mutations: {
        SET_SIDE_QUEST_RESULT(state, sideQuestResult) {
            state.sideQuestResult = sideQuestResult;
        },
        SET_SIDE_QUEST_COMBAT_SQUAD(state, combatSquad) {
            state.sideQuestCombatSquad = combatSquad;
        },
        SET_SIDE_QUEST_COMBAT_GROUP(state, combatGroup) {
            state.sideQuestEnemyGroup = combatGroup;
        },
        SET_SIDE_QUEST_COMBAT_SQUAD_TOTAL_HEALTH(state, totalHealth) {
            state.sqReplaySquadTotalHealth = totalHealth;
        },
        SET_SIDE_QUEST_COMBAT_GROUP_TOTAL_HEALTH(state, totalHealth) {
            state.sqReplayEnemyTotalHealth = totalHealth;
        },
        PUSH_SIDE_QUEST_EVENTS(state, sqEvents) {
            state.sideQuestEvents = _.union(state.sideQuestEvents, sqEvents);
        },
        PUSH_TRIGGERED_SIDE_QUEST_EVENTS(state, triggeredEvents) {
            state.triggeredSideQuestEvents = _.union(state.triggeredSideQuestEvents, triggeredEvents);
        },
        SET_CURRENT_SIDE_QUEST_EVENTS(state, currentEvents) {
            state.currentSideQuestEvents = currentEvents;
        },
        INCREMENT_SIDE_QUEST_MOMENT(state) {
            state.sideQuestMoment++;
        },
        UNPAUSE_SIDE_QUEST_REPLAY(state) {
            state.sideQuestReplayPaused = false;
        },
        PAUSE_SIDE_QUEST_REPLAY(state) {
            state.sideQuestReplayPaused = true;
        }
    },

    actions: {
        async setupSideQuestReplay({commit}, {sideQuestResult}) {

            commit('SET_SIDE_QUEST_RESULT', sideQuestResult);

            let battleGround = await sideQuestResultApi.getBattleground(sideQuestResult.uuid);
            let combatSquad = new CombatSquad(battleGround.data.combat_squad);
            let enemyGroup = new SideQuestGroup(battleGround.data.side_quest_group);

            commit('SET_SIDE_QUEST_COMBAT_SQUAD', combatSquad);
            commit('SET_SIDE_QUEST_COMBAT_GROUP', enemyGroup);

            commit('SET_ALLY_HEALTH_PERCENTS', combatSquad.getHealthPercentsObject());
            commit('SET_ALLY_TOTAL_INITIAL_HEALTH', combatSquad.getHealthSum({combatPositionIDs: [1,2,3], healthProperty: 'initialHealth'}));

            commit('SET_ENEMY_HEALTH_PERCENTS', enemyGroup.getHealthPercentsObject());
            commit('SET_ENEMY_TOTAL_INITIAL_HEALTH', enemyGroup.getHealthSum({combatPositionIDs: [1,2,3], healthProperty: 'initialHealth'}));

            // let reducer = (initialHealthSum, initialHealth) => initialHealthSum + initialHealth;
            // commit('SET_SIDE_QUEST_COMBAT_SQUAD_TOTAL_HEALTH', combatSquad.combatants.map(combatant => combatant.initialHealth).reduce(reducer, 0));
            // commit('SET_SIDE_QUEST_COMBAT_GROUP_TOTAL_HEALTH', enemyGroup.combatants.map(combatant => combatant.initialHealth).reduce(reducer, 0));
            //

            let retrieveEvents = true;
            let page = 1;
            while (retrieveEvents && page <= 200) {
                let eventsResponse = await sideQuestResultApi.getEvents(sideQuestResult.uuid, page);
                let sideQuestEvents = eventsResponse.data.map(sqEvent => new CombatEvent(sqEvent));
                commit('PUSH_SIDE_QUEST_EVENTS', sideQuestEvents);
                retrieveEvents = (eventsResponse.meta.last_page !== page);
                page++;
            }
        },

        async runSideQuestReplay({commit, state, rootState}) {

            commit('UNPAUSE_SIDE_QUEST_REPLAY');

            while (! state.sideQuestReplayPaused) {

                let filteredByMomentEvents = state.sideQuestEvents.filter(sqEvent => sqEvent.moment === state.sideQuestMoment);

                if (filteredByMomentEvents.length > 0) {

                    /*
                     * Squad Turn
                     */
                    commit('CLEAR_ALLY_DAMAGES');
                    commit('CLEAR_ALLY_BLOCKS');
                    let squadTurnEvents = filteredByMomentEvents.filter(sqEvent => [
                        'hero-damages-minion',
                        'hero-kills-minion',
                        'minion-blocks-hero'
                    ].includes(sqEvent.eventType));

                    commit('SET_CURRENT_SIDE_QUEST_EVENTS', squadTurnEvents);
                    commit('PUSH_TRIGGERED_SIDE_QUEST_EVENTS', squadTurnEvents);

                    let sideQuestGroup = _.cloneDeep(state.sideQuestEnemyGroup);
                    squadTurnEvents.filter(sqEvent => sqEvent.eventType === 'hero-damages-minion').forEach(function (triggeredEvent) {
                        let combatMinion = triggeredEvent.data.minion;
                        let index = sideQuestGroup.combatants.findIndex(minion => minion.combatantUuid === combatMinion.combatantUuid);
                        if (index !== -1) {
                            let matchingMinion = sideQuestGroup.combatants[index];
                            matchingMinion.currentHealth = combatMinion.health;
                            sideQuestGroup.combatants.splice(index, 1, matchingMinion);
                        }
                    });

                    commit('SET_SIDE_QUEST_COMBAT_GROUP', sideQuestGroup);
                    let enemyDamages = convertEventsToEnemyDamages(squadTurnEvents, state.sideQuestEnemyGroup);
                    commit('SET_ENEMY_DAMAGES', enemyDamages);

                    let enemyBlocks = convertEventsToEnemyBlocks(squadTurnEvents, state.sideQuestEnemyGroup);
                    commit('SET_ENEMY_BLOCKS', enemyBlocks);

                    commit('SET_ENEMY_HEALTH_PERCENTS', sideQuestGroup.getHealthPercentsObject());

                    await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed));

                    /*
                     * Side Quest Group Turn
                     */
                    commit('CLEAR_ENEMY_DAMAGES');
                    commit('CLEAR_ENEMY_BLOCKS');
                    let sideQuestGroupTurnEvents = filteredByMomentEvents.filter(sqEvent => [
                        'minion-damages-hero',
                        'minion-kills-hero',
                        'hero-blocks-minion'
                    ].includes(sqEvent.eventType));

                    commit('SET_CURRENT_SIDE_QUEST_EVENTS', sideQuestGroupTurnEvents);
                    commit('PUSH_TRIGGERED_SIDE_QUEST_EVENTS', sideQuestGroupTurnEvents);

                    let combatSquad = _.cloneDeep(state.sideQuestCombatSquad);

                    sideQuestGroupTurnEvents.filter(sqEvent => sqEvent.eventType === 'minion-damages-hero').forEach(function (triggeredEvent) {
                        let combatHero = triggeredEvent.data.hero;
                        let index = combatSquad.combatants.findIndex(hero => hero.combatantUuid === combatHero.combatantUuid);
                        if (index !== -1) {
                            let matchingHero = combatSquad.combatants[index];
                            matchingHero.currentHealth = combatHero.health;
                            combatSquad.combatants.splice(index, 1, matchingHero);
                        }
                    });

                    commit('SET_SIDE_QUEST_COMBAT_SQUAD', combatSquad);
                    let allyDamages = convertEventsToAllyDamages(sideQuestGroupTurnEvents, state.sideQuestCombatSquad);
                    commit('SET_ALLY_DAMAGES', allyDamages);

                    let allyBLocks = convertEventsToAllyBlocks(sideQuestGroupTurnEvents, state.sideQuestCombatSquad);
                    commit('SET_ALLY_BLOCKS', allyBLocks);

                    commit('SET_ALLY_HEALTH_PERCENTS', combatSquad.getHealthPercentsObject());

                    await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed));
                } else {

                    commit('SET_CURRENT_SIDE_QUEST_EVENTS', []);
                    commit('CLEAR_ENEMY_DAMAGES');
                    commit('CLEAR_ENEMY_BLOCKS');
                    commit('CLEAR_ALLY_DAMAGES');
                    commit('CLEAR_ALLY_BLOCKS');
                    await new Promise(resolve => setTimeout(resolve, Math.ceil(rootState.battlefieldModule.battlefieldSpeed/4)));
                }

                commit('INCREMENT_SIDE_QUEST_MOMENT');
            }
        },
        pauseSideQuestReplay({commit}) {
            commit('PAUSE_SIDE_QUEST_REPLAY');
        }
    }
};

function convertEventsToAllyDamages(sqEvents, combatSquad) {
    let damageEvents = sqEvents.filter(sqEvent => sqEvent.eventType === 'minion-damages-hero');

    return {
        'front-line': convertToDamagesByCombatPosition(damageEvents, 1, combatSquad, 'hero'),
        'back-line': convertToDamagesByCombatPosition(damageEvents, 2, combatSquad, 'hero'),
        'high-ground': convertToDamagesByCombatPosition(damageEvents, 3, combatSquad, 'hero'),
    }
}

function convertEventsToEnemyDamages(sqEvents, sideQuestEnemyGroup) {
    let damageEvents = sqEvents.filter(sqEvent => sqEvent.eventType === 'hero-damages-minion');

    return {
        'front-line': convertToDamagesByCombatPosition(damageEvents, 1, sideQuestEnemyGroup, 'minion'),
        'back-line': convertToDamagesByCombatPosition(damageEvents, 2, sideQuestEnemyGroup, 'minion'),
        'high-ground': convertToDamagesByCombatPosition(damageEvents, 3, sideQuestEnemyGroup, 'minion'),
    }
}

function convertEventsToAllyBlocks(sqEvents, combatSquad) {
    let blockEvents = sqEvents.filter(sqEvent => sqEvent.eventType === 'hero-blocks-minion');

    // We'll map into array of empty objects so any watchers pick up changes
    return {
        'front-line': filterEventsByCombatPosition(blockEvents, 1, combatSquad, 'hero').map(event => new Object({})),
        'back-line': filterEventsByCombatPosition(blockEvents, 2, combatSquad, 'hero').map(event => new Object({})),
        'high-ground': filterEventsByCombatPosition(blockEvents, 3, combatSquad, 'hero').map(event => new Object({})),
    }
}

function convertEventsToEnemyBlocks(sqEvents, sideQuestGroup) {
    let blockEvents = sqEvents.filter(sqEvent => sqEvent.eventType === 'minion-blocks-hero');

    // We'll map into array of empty objects so any watchers pick up changes
    return {
        'front-line': filterEventsByCombatPosition(blockEvents, 1, sideQuestGroup, 'minion').map(event => new Object({})),
        'back-line': filterEventsByCombatPosition(blockEvents, 2, sideQuestGroup, 'minion').map(event => new Object({})),
        'high-ground': filterEventsByCombatPosition(blockEvents, 3, sideQuestGroup, 'minion').map(event => new Object({})),
    };
}

function convertToDamagesByCombatPosition(sqEvents, combatPositionID, combatGroup, combatantKey) {
    return filterEventsByCombatPosition(sqEvents, combatPositionID, combatGroup, combatantKey).map(sqEvent => sqEvent.data.damage);
}

function filterEventsByCombatPosition(sqEvents, combatPositionID, combatGroup, combatantKey) {
    return sqEvents.filter(function (sqEvent) {
        let matchingCombatant = combatGroup.combatants.find(combatant => combatant.combatantUuid === sqEvent.data[combatantKey].combatantUuid);
        if (matchingCombatant) {
            return matchingCombatant.combatPositionID === combatPositionID;
        }
        return false;
    });
}
