import * as sideQuestResultApi from '../../api/sideQuestResultApi';
import CombatGroup from "../../models/CombatGroup";
export default {

    state: {
        sideQuestResult: null,
        currentMoment: 0,
        sideQuestCombatSquad: null,
        sideQuestEnemyGroup: null
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
