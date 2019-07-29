
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
        setCurrentWeek({commit}, payload) {
            commit('SET_CURRENT_WEEK', payload)
        },
    }
};