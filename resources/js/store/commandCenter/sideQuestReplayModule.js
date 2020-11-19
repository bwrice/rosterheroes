import SideQuestResult from "../../models/SideQuestResult";

export default {

    state: {
        sideQuestResult: null,
        currentMoment: 0
    },

    getters: {
        _sideQuestResult(state) {
            return state.sideQuestResult;
        }
    },
    mutations: {
        SET_SIDE_QUEST_RESULT(state, sideQuestResult) {
            state.sideQuestResult = sideQuestResult;
        }
    },

    actions: {

    }
};
