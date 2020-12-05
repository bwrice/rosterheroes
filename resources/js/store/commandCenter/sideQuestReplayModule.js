import * as sideQuestResultApi from '../../api/sideQuestResultApi';
import CombatEvent from "../../models/CombatEvent";
import CombatSquad from "../../models/CombatSquad";
import SideQuestGroup from "../../models/SideQuestGroup";
import BattlefieldAttackEvent from "../../models/battlefield/BattlefieldAttackEvent";
import BattlefieldDamageEvent from "../../models/battlefield/BattlefieldDamageEvent";
import BattlefieldBlockEvent from "../../models/battlefield/BattlefieldBlockEvent";

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

            let squadTotalHealth = state.sideQuestCombatSquad.getHealthSum({combatPositionIDs: [1,2,3], healthProperty: 'initialHealth'});
            let enemyGroupTotalHealth = state.sideQuestEnemyGroup.getHealthSum({combatPositionIDs: [1,2,3], healthProperty: 'initialHealth'});

            let battlefieldAttacks = [];

            while (! state.sideQuestReplayPaused) {

                let filteredByMomentEvents = state.sideQuestEvents.filter(sqEvent => sqEvent.moment === state.sideQuestMoment);

                if (filteredByMomentEvents.length > 0) {

                    /*
                     * Squad Turn
                     */
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

                    battlefieldAttacks = convertSquadAttacksToBattlefieldAttacks(squadTurnEvents, state.sideQuestCombatSquad, state.sideQuestEnemyGroup, enemyGroupTotalHealth);

                    commit('SET_BATTLEFIELD_ATTACKS', battlefieldAttacks);

                    commit('SET_SIDE_QUEST_COMBAT_GROUP', sideQuestGroup);
                    commit('SET_ENEMY_HEALTH_PERCENTS', sideQuestGroup.getHealthPercentsObject());

                    await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed));

                    /*
                     * Side Quest Group Turn
                     */
                    let sideQuestGroupTurnEvents = filteredByMomentEvents.filter(sqEvent => [
                        'minion-damages-hero',
                        'minion-kills-hero',
                        'hero-blocks-minion'
                    ].includes(sqEvent.eventType));

                    battlefieldAttacks = convertEnemyGroupAttacksToBattlefieldAttacks(sideQuestGroupTurnEvents, state.sideQuestEnemyGroup, state.sideQuestCombatSquad, squadTotalHealth);

                    commit('SET_BATTLEFIELD_ATTACKS', battlefieldAttacks);

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
                    commit('SET_ALLY_HEALTH_PERCENTS', combatSquad.getHealthPercentsObject());

                    await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed));
                } else {

                    commit('SET_CURRENT_SIDE_QUEST_EVENTS', []);

                    commit('SET_BATTLEFIELD_ATTACKS', []);
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

function convertSquadAttacksToBattlefieldAttacks(sqEvents, combatSquad, enemyGroup, enemyGroupTotalHealth) {

    return convertEventsToBattlefieldAttacks({
        sqEvents: sqEvents,
        attackingGroup: combatSquad,
        targetGroup: enemyGroup,
        damageEventTypes: ['hero-damages-minion'],
        blockEventTypes: ['minion-blocks-hero'],
        targetGroupTotalHealth: enemyGroupTotalHealth,
        attackingCombatantKey: 'hero',
        targetCombatantKey: 'minion',
        allySideAttacking: true
    });
}

function convertEnemyGroupAttacksToBattlefieldAttacks(sqEvents, sideQuestEnemyGroup, combatSquad, combatSquadTotalHealth) {

    return convertEventsToBattlefieldAttacks({
        sqEvents: sqEvents,
        attackingGroup: sideQuestEnemyGroup,
        targetGroup: combatSquad,
        damageEventTypes: ['minion-damages-hero'],
        blockEventTypes: ['hero-blocks-minion'],
        targetGroupTotalHealth: combatSquadTotalHealth,
        attackingCombatantKey: 'minion',
        targetCombatantKey: 'hero',
        allySideAttacking: false
    });
}

function convertEventsToBattlefieldAttacks(
    {
        sqEvents,
        damageEventTypes,
        blockEventTypes,
        attackingGroup,
        targetGroup,
        targetGroupTotalHealth,
        attackingCombatantKey,
        targetCombatantKey,
        allySideAttacking
    }) {

    // Filter out any invents that aren't attacks
    let attackEvents = sqEvents.filter(function (sqEvent) {
        return damageEventTypes.includes(sqEvent.eventType) || blockEventTypes.includes(sqEvent.eventType)
    });

    // Group events by attackUuid and get an array of attackUuids by the grouped property keys
    let groupedEvents = _.groupBy(attackEvents, (sqEvent) => sqEvent.data.attack.uuid);
    let attackUuids = Object.keys(groupedEvents);

    return attackUuids.map(function (attackUuid) {
        let eventsForAttack = groupedEvents[attackUuid];
        let damageEvents = eventsForAttack.filter(sqEvent => damageEventTypes.includes(sqEvent.eventType));

        let battlefieldDamages = damageEvents.map(function (sqEvent) {
            let damage = sqEvent.data.damage;
            let magnitude = targetGroup ? 500 * (damage/targetGroupTotalHealth) : 1;
            let matchingTargetCombatant = targetGroup.combatants.find(combatant => combatant.combatantUuid === sqEvent.data[targetCombatantKey].combatantUuid);
            let targetCombatPositionName = getCombatPositionName(matchingTargetCombatant.combatPositionID);

            return new BattlefieldDamageEvent({
                damage: damage,
                magnitude: magnitude,
                allySide: ! allySideAttacking,
                combatPositionName: targetCombatPositionName
            })
        });

        let blockEvents = eventsForAttack.filter(sqEvent => blockEventTypes.includes(sqEvent.eventType));

        let battlefieldBlocks = blockEvents.map(function (sqEvent) {
            let matchingTargetCombatant = targetGroup.combatants.find(combatant => combatant.combatantUuid === sqEvent.data[targetCombatantKey].combatantUuid);
            let targetCombatPositionName = getCombatPositionName(matchingTargetCombatant.combatPositionID);

            return new BattlefieldBlockEvent({
                allySide: ! allySideAttacking,
                combatPositionName: targetCombatPositionName
            })
        });

        // We can use first event, since they all should have the same attacker
        let firstEvent = _.first(eventsForAttack);
        let matchingAttackingCombatant = attackingGroup.combatants.find(combatant => combatant.combatantUuid === firstEvent.data[attackingCombatantKey].combatantUuid);
        let attackerCombatPositionName = getCombatPositionName(matchingAttackingCombatant.combatPositionID);
        return new BattlefieldAttackEvent({
            battlefieldDamages: battlefieldDamages,
            battlefieldBlocks: battlefieldBlocks,
            allySide: allySideAttacking,
            combatPositionName: attackerCombatPositionName
        })
    });
}



function getCombatPositionName(combatPositionID) {
    switch (combatPositionID) {
        case 1:
            return 'front-line';
        case 2:
            return 'back-line';
        case 3:
            return 'high-ground';
        default:
            console.log("CANNOT FIND");
            return 'high-ground'
    }
}
