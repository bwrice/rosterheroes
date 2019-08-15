import * as weekApi from '../../api/weekApi';

export default {

    state: {
        week: null
    },

    getters: {
        _currentWeek(state) {
            return state.week;
        }
    },
    mutations: {
        SET_CURRENT_WEEK(state, payload) {
            state.week = payload;
        }
    },

    actions: {
        async updateCurrentWeek({commit}) {
            let week = await weekApi.getCurrentWeek();
            commit('SET_CURRENT_WEEK', week)
        },
    }
};
