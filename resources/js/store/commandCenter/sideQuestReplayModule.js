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
        triggeredSideQuestEvents: []
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
        _triggeredSideQuestEvents(state) {
            return state.triggeredSideQuestEvents;
        },
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
        PUSH_SIDE_QUEST_EVENTS(state, sqEvents) {
            state.sideQuestEvents = _.union(state.sideQuestEvents, sqEvents);
        },
        PUSH_TRIGGERED_SIDE_QUEST_EVENTS(state, triggeredEvents) {
            state.triggeredSideQuestEvents = _.union(state.triggeredSideQuestEvents, triggeredEvents);
        },
        INCREMENT_SIDE_QUEST_MOMENT(state) {
            state.sideQuestMoment++;
        }
    },

    actions: {
        async setupSideQuestReplay({commit}, {sideQuestResult}) {
            commit('SET_SIDE_QUEST_RESULT', sideQuestResult);
            let battleGround = await sideQuestResultApi.getBattleground(sideQuestResult.uuid);
            commit('SET_SIDE_QUEST_COMBAT_SQUAD', new CombatSquad(battleGround.data.combat_squad));
            commit('SET_SIDE_QUEST_COMBAT_GROUP', new SideQuestGroup(battleGround.data.side_quest_group));

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
        triggerSideQuestMoment({commit, state}) {
            commit('INCREMENT_SIDE_QUEST_MOMENT');
            console.log("MOMENT");
            console.log(state.sideQuestMoment);
            let triggeredEvents = state.sideQuestEvents.filter(sqEvent => sqEvent.moment === state.sideQuestMoment);
            console.log(triggeredEvents);
            commit('PUSH_TRIGGERED_SIDE_QUEST_EVENTS', triggeredEvents);
        }
    }
};
