import * as sideQuestResultApi from '../../api/sideQuestResultApi';
import CombatGroup from "../../models/CombatGroup";
export default {

    state: {
        sideQuestResult: null,
        currentMoment: 0,
        sideQuestCombatSquad: null,
        sideQuestCombatGroup: null
    },

    getters: {
        _sideQuestResult(state) {
            return state.sideQuestResult;
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
            state.sideQuestCombatGroup = combatGroup;
        }
    },

    actions: {
        async setupSideQuestReplay({commit}, {sideQuestResult}) {
            commit('SET_SIDE_QUEST_RESULT', sideQuestResult);
            let battleGround = await sideQuestResultApi.getBattleground(sideQuestResult.uuid);
            commit('SET_SIDE_QUEST_COMBAT_SQUAD', new CombatGroup({
                combatants: battleGround.data.combat_squad.combat_heroes
            }));
            commit('SET_SIDE_QUEST_COMBAT_GROUP', new CombatGroup({
                combatants: battleGround.data.side_quest_group.combat_minions
            }));
        }
    }
};
