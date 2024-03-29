import * as sideQuestResultApi from '../../api/sideQuestResultApi';
import SideQuestEvent from "../../models/SideQuestEvent";
import CombatSquad from "../../models/CombatSquad";
import SideQuestGroup from "../../models/SideQuestGroup";
import BattlefieldAttackEvent from "../../models/battlefield/BattlefieldAttackEvent";
import BattlefieldDamageEvent from "../../models/battlefield/BattlefieldDamageEvent";
import BattlefieldBlockEvent from "../../models/battlefield/BattlefieldBlockEvent";
import BattlefieldDeathEvent from "../../models/battlefield/BattlefieldDeathEvent";
import CombatEventMessage from "../../models/CombatEventMessage";
import SideQuestResult from "../../models/SideQuestResult";

export default {

    state: {
        initialSideQuestCombatSquad: null,
        initialSideQuestEnemyGroup: null,
        sideQuestResult: new SideQuestResult({}),
        sideQuestMoment: 0,
        sideQuestCombatSquad: null,
        sideQuestEnemyGroup: null,
        sideQuestEvents: [],
        triggeredSideQuestMessages: [],
        currentSideQuestEvents: [],
        sideQuestReplayPaused: true,
        sideQuestReplayDisabled: true,
        sideQuestEndEvent: null
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
        _sideQuestMoment(state) {
            return state.sideQuestMoment;
        },
        _triggeredSideQuestMessages(state) {
            return state.triggeredSideQuestMessages;
        },
        _sideQuestReplayPaused(state) {
            return state.sideQuestReplayPaused;
        },
        _sideQuestReplayDisabled(state) {
            return state.sideQuestReplayDisabled;
        },
        _currentSideQuestEvents(state) {
            return state.currentSideQuestEvents;
        },
        _sideQuestEndEvent(state) {
            return state.sideQuestEndEvent;
        },
    },
    mutations: {
        SET_SIDE_QUEST_RESULT(state, sideQuestResult) {
            state.sideQuestResult = sideQuestResult;
        },
        SET_INITIAL_SIDE_QUEST_COMBAT_SQUAD(state, combatSquad) {
            state.initialSideQuestCombatSquad = combatSquad;
        },
        SET_INITIAL_SIDE_QUEST_COMBAT_GROUP(state, combatGroup) {
            state.initialSideQuestEnemyGroup = combatGroup;
        },
        SET_SIDE_QUEST_COMBAT_SQUAD(state, combatSquad) {
            state.sideQuestCombatSquad = combatSquad;
        },
        SET_SIDE_QUEST_COMBAT_GROUP(state, combatGroup) {
            state.sideQuestEnemyGroup = combatGroup;
        },
        CLEAR_SIDE_QUEST_EVENTS(state) {
            state.sideQuestEvents = [];
        },
        PUSH_SIDE_QUEST_EVENTS(state, sqEvents) {
            state.sideQuestEvents = _.union(state.sideQuestEvents, sqEvents);
        },
        PUSH_TRIGGERED_SIDE_QUEST_MESSAGE(state, triggeredEvent) {
            state.triggeredSideQuestMessages.unshift(triggeredEvent);
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
        },
        DISABLE_SIDE_QUEST_REPLAY(state) {
            state.sideQuestReplayDisabled = true;
        },
        ENABLE_SIDE_QUEST_REPLAY(state) {
            state.sideQuestReplayDisabled = false;
        },
        RESET_SIDE_QUEST_REPLAY(state) {
            state.sideQuestEndEvent = null;
            state.sideQuestMoment = 0;
            state.sideQuestReplayPaused = true;
            state.triggeredSideQuestMessages = [];
            state.currentSideQuestEvents = [];
        },
        SET_SIDE_QUEST_END_EVENT(state, endEvent) {
            state.sideQuestEndEvent = endEvent;
        },
        CLEAR_SIDE_QUEST_END_EVENT(state) {
            state.sideQuestEndEvent = null;
        }
    },

    actions: {
        async setupSideQuestReplay({commit, dispatch}, sideQuestResult) {

            commit('DISABLE_SIDE_QUEST_REPLAY');
            commit('RESET_BATTLEFIELD');
            commit('RESET_SIDE_QUEST_REPLAY');

            commit('SET_SIDE_QUEST_RESULT', sideQuestResult);

            let battleGround = await sideQuestResultApi.getBattleground(sideQuestResult.uuid);
            let combatSquad = new CombatSquad(battleGround.data.combat_squad);
            let enemyGroup = new SideQuestGroup(battleGround.data.side_quest_group);

            commit('SET_INITIAL_SIDE_QUEST_COMBAT_SQUAD', combatSquad);
            commit('SET_INITIAL_SIDE_QUEST_COMBAT_GROUP', enemyGroup);
            commit('SET_SIDE_QUEST_COMBAT_SQUAD', _.cloneDeep(combatSquad));
            commit('SET_SIDE_QUEST_COMBAT_GROUP', _.cloneDeep(enemyGroup));

            commit('SET_ALLY_HEALTH_PERCENTS', combatSquad.getHealthPercentsObject());
            commit('SET_ENEMY_HEALTH_PERCENTS', enemyGroup.getHealthPercentsObject());

            commit('CLEAR_SIDE_QUEST_EVENTS');
            let retrieveEvents = true;
            let page = 1;
            while (retrieveEvents && page <= 200) {
                let eventsResponse = await sideQuestResultApi.getEvents(sideQuestResult.uuid, page);
                let sideQuestEvents = eventsResponse.data.map(sqEvent => new SideQuestEvent(sqEvent));
                commit('PUSH_SIDE_QUEST_EVENTS', sideQuestEvents);
                retrieveEvents = (eventsResponse.meta.last_page !== page);
                page++;
            }
            commit('ENABLE_SIDE_QUEST_REPLAY');
        },

        async rebuildSideQuestReplay({commit, state, rootState}) {

            // Pause, and let any unprocessed events finish
            commit('PAUSE_SIDE_QUEST_REPLAY');
            commit('DISABLE_SIDE_QUEST_REPLAY');
            state.sideQuestReplayDisabled = true;
            await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed * 2));

            let combatSquad = _.cloneDeep(state.initialSideQuestCombatSquad);
            let enemyGroup = _.cloneDeep(state.initialSideQuestEnemyGroup);

            commit('RESET_BATTLEFIELD');
            commit('SET_SIDE_QUEST_COMBAT_SQUAD', combatSquad);
            commit('SET_SIDE_QUEST_COMBAT_GROUP', enemyGroup);

            commit('RESET_SIDE_QUEST_REPLAY');
            commit('SET_ALLY_HEALTH_PERCENTS', combatSquad.getHealthPercentsObject());
            commit('SET_ENEMY_HEALTH_PERCENTS', enemyGroup.getHealthPercentsObject());
            commit('ENABLE_SIDE_QUEST_REPLAY');
        },

        async runSideQuestReplay({commit, state, rootState}) {

            commit('UNPAUSE_SIDE_QUEST_REPLAY');

            let squadTotalHealth = state.sideQuestCombatSquad.getHealthSum({combatPositionIDs: [1,2,3], healthProperty: 'initialHealth'});
            let enemyGroupTotalHealth = state.sideQuestEnemyGroup.getHealthSum({combatPositionIDs: [1,2,3], healthProperty: 'initialHealth'});

            let battlefieldAttacks = [];
            let enemyGroupDeaths = [];
            let combatSquadDeaths = [];

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
                    pushTriggeredSideQuestMessages({
                        triggeredEvents: squadTurnEvents,
                        squadSnapshot: rootState.focusedCampaignModule.squadSnapshot,
                        sideQuestSnapshot: state.sideQuestResult.sideQuestSnapshot,
                        commit: commit,
                        battlefieldSpeed: rootState.battlefieldModule.battlefieldSpeed
                    });

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

                    if (squadTurnEvents.length > 0) {
                        await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed));
                    }

                    enemyGroupDeaths = convertSquadEventsIntoBattlefieldDeaths(squadTurnEvents, state.sideQuestEnemyGroup);

                    if (enemyGroupDeaths.length > 0) {
                        commit('PUSH_BATTLEFIELD_DEATHS', enemyGroupDeaths);
                        commit('SET_BATTLEFIELD_ATTACKS', []);
                    }

                    /*
                     * Side Quest Group Turn
                     */
                    let sideQuestGroupTurnEvents = filteredByMomentEvents.filter(sqEvent => [
                        'minion-damages-hero',
                        'minion-kills-hero',
                        'hero-blocks-minion'
                    ].includes(sqEvent.eventType));

                    commit('SET_CURRENT_SIDE_QUEST_EVENTS', sideQuestGroupTurnEvents);
                    pushTriggeredSideQuestMessages({
                        triggeredEvents: sideQuestGroupTurnEvents,
                        squadSnapshot: rootState.focusedCampaignModule.squadSnapshot,
                        sideQuestSnapshot: state.sideQuestResult.sideQuestSnapshot,
                        commit: commit,
                        battlefieldSpeed: rootState.battlefieldModule.battlefieldSpeed
                    });

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

                    if (sideQuestGroupTurnEvents.length > 0) {
                        await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed));
                    }

                    combatSquadDeaths = convertEnemyGroupEventsIntoBattlefieldDeaths(sideQuestGroupTurnEvents, state.sideQuestCombatSquad);

                    if (combatSquadDeaths.length > 0) {
                        commit('PUSH_BATTLEFIELD_DEATHS', combatSquadDeaths);
                        commit('SET_BATTLEFIELD_ATTACKS', []);
                    }

                    let endEvent = filteredByMomentEvents.find(sqEvent => ['side-quest-victory', 'side-quest-defeat'].includes(sqEvent.eventType));
                    if (endEvent) {
                        commit('SET_BATTLEFIELD_ATTACKS', []);
                        commit('PAUSE_SIDE_QUEST_REPLAY');
                        await new Promise(resolve => setTimeout(resolve, rootState.battlefieldModule.battlefieldSpeed + 1000));
                        commit('SET_SIDE_QUEST_END_EVENT', endEvent);
                    }

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
        },

        clearSideQuestEndEvent({commit}) {
            commit('CLEAR_SIDE_QUEST_END_EVENT');
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
            return 'high-ground'
    }
}

function convertSquadEventsIntoBattlefieldDeaths(squadTurnEvents, sideQuestEnemyGroup) {
    let deathEvents = squadTurnEvents.filter(sqEvent => sqEvent.eventType === 'hero-kills-minion');

    return convertEventsIntoBattlefieldDeaths({
       filteredEvents: deathEvents,
       targetGroup: sideQuestEnemyGroup,
       targetCombatantKey: 'minion',
       allySideAttacking: true
    });
}

function convertEnemyGroupEventsIntoBattlefieldDeaths(enemyTurnEvents, combatSquad) {
    let deathEvents = enemyTurnEvents.filter(sqEvent => sqEvent.eventType === 'minion-kills-hero');
    return convertEventsIntoBattlefieldDeaths({
       filteredEvents: deathEvents,
       targetGroup: combatSquad,
       targetCombatantKey: 'hero',
       allySideAttacking: false
    });
}

function convertEventsIntoBattlefieldDeaths(
    {
        filteredEvents,
        targetGroup,
        targetCombatantKey,
        allySideAttacking
    }
    ) {
    return filteredEvents.map(function (killEvent) {
        let matchingCombatant = targetGroup.combatants.find(function (combatant) {
           return combatant.combatantUuid === killEvent.data[targetCombatantKey].combatantUuid;
        });

        let combatPositionName = getCombatPositionName(matchingCombatant.combatPositionID);
        return new BattlefieldDeathEvent({
            combatPositionName: combatPositionName,
            allySide: ! allySideAttacking
        })
    })
}

async function pushTriggeredSideQuestMessages(
    {
        triggeredEvents,
        squadSnapshot,
        sideQuestSnapshot,
        commit,
        battlefieldSpeed
    }) {

    let divider = triggeredEvents.length ? triggeredEvents.length * 2 : 1;
    let delayBetweenEvents = Math.floor((battlefieldSpeed)/divider);
    for (let i = 0; i < triggeredEvents.length; i++) {
        let eventMessage = convertEventToCombatMessage(triggeredEvents[i], squadSnapshot, sideQuestSnapshot);
        commit('PUSH_TRIGGERED_SIDE_QUEST_MESSAGE', eventMessage);
        await new Promise(resolve => setTimeout(resolve, delayBetweenEvents));
    }
}

function convertEventToCombatMessage(sqEvent, squadSnapshot, sideQuestSnapshot) {
    let message = 'N/A';
    let allySide = true;
    switch (sqEvent.eventType) {
        case 'hero-damages-minion':
            message = convertHeroDamagesMinionToMessage(sqEvent, squadSnapshot, sideQuestSnapshot);
            allySide = true;
            break;
        case 'minion-blocks-hero':
            message = convertMinionBlocksHeroToMessage(sqEvent, squadSnapshot, sideQuestSnapshot);
            allySide = true;
            break;
        case 'hero-kills-minion':
            message = convertHeroKillsMinionToMessage(sqEvent, squadSnapshot, sideQuestSnapshot);
            allySide = true;
            break;
        case 'minion-damages-hero':
            message = convertMinionDamagesHeroToMessage(sqEvent, squadSnapshot, sideQuestSnapshot);
            allySide = false;
            break;
        case 'hero-blocks-minion':
            message = convertHeroBlocksMinionToMessage(sqEvent, squadSnapshot, sideQuestSnapshot);
            allySide = false;
            break;
        case 'minion-kills-hero':
            message = convertMinionKillsHeroToMessage(sqEvent, squadSnapshot, sideQuestSnapshot);
            allySide = false;
            break;
    }

    return new CombatEventMessage({
        eventUuid: sqEvent.uuid,
        moment: sqEvent.moment,
        message: message,
        allySide: allySide
    })
}

function convertHeroDamagesMinionToMessage(sqEvent, squadSnapshot, sideQuestSnapshot) {
    let heroSnapshot = squadSnapshot.heroSnapshot(sqEvent.data['hero'].sourceUuid);
    let minionSnapshot = sideQuestSnapshot.minionSnapshot(sqEvent.data['minion'].sourceUuid);
    let attackSnapshot = heroSnapshot.attackSnapshot(sqEvent.data['attack'].sourceUuid);
    return '[' + attackSnapshot.name + '] ' + heroSnapshot.name + ' attacks ' + minionSnapshot.name + ' for '
        + sqEvent.data['damage'] + ' damage';
}

function convertMinionDamagesHeroToMessage(sqEvent, squadSnapshot, sideQuestSnapshot) {
    let minionSnapshot = sideQuestSnapshot.minionSnapshot(sqEvent.data['minion'].sourceUuid);
    let heroSnapshot = squadSnapshot.heroSnapshot(sqEvent.data['hero'].sourceUuid);
    let attackSnapshot = minionSnapshot.attackSnapshot(sqEvent.data['attack'].sourceUuid);
    return '[' + attackSnapshot.name + '] ' + minionSnapshot.name + ' attacks ' + heroSnapshot.name + ' for '
        + sqEvent.data['damage'] + ' damage';
}

function convertMinionBlocksHeroToMessage(sqEvent, squadSnapshot, sideQuestSnapshot) {
    let heroSnapshot = squadSnapshot.heroSnapshot(sqEvent.data['hero'].sourceUuid);
    let minionSnapshot = sideQuestSnapshot.minionSnapshot(sqEvent.data['minion'].sourceUuid);
    let attackSnapshot = heroSnapshot.attackSnapshot(sqEvent.data['attack'].sourceUuid);
    return '[' + attackSnapshot.name + '] ' + minionSnapshot.name + ' blocks attack from ' + heroSnapshot.name;
}

function convertHeroBlocksMinionToMessage(sqEvent, squadSnapshot, sideQuestSnapshot) {
    let minionSnapshot = sideQuestSnapshot.minionSnapshot(sqEvent.data['minion'].sourceUuid);
    let heroSnapshot = squadSnapshot.heroSnapshot(sqEvent.data['hero'].sourceUuid);
    let attackSnapshot = minionSnapshot.attackSnapshot(sqEvent.data['attack'].sourceUuid);
    return '[' + attackSnapshot.name + '] ' + heroSnapshot.name + ' blocks attack from ' + minionSnapshot.name;
}

function convertHeroKillsMinionToMessage(sqEvent, squadSnapshot, sideQuestSnapshot) {
    let heroSnapshot = squadSnapshot.heroSnapshot(sqEvent.data['hero'].sourceUuid);
    let minionSnapshot = sideQuestSnapshot.minionSnapshot(sqEvent.data['minion'].sourceUuid);
    return minionSnapshot.name + ' falls in battle to ' + heroSnapshot.name;
}

function convertMinionKillsHeroToMessage(sqEvent, squadSnapshot, sideQuestSnapshot) {
    let heroSnapshot = squadSnapshot.heroSnapshot(sqEvent.data['hero'].sourceUuid);
    let minionSnapshot = sideQuestSnapshot.minionSnapshot(sqEvent.data['minion'].sourceUuid);
    return heroSnapshot.name + ' falls in battle to ' + minionSnapshot.name;
}
