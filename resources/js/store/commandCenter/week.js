import Week from '../../models/Week';

export default {

    state: {
        week: new Week({})
    },

    getters: {
        _currentWeek(state) {
            return state.week;
        }
    },
    mutations: {
        SET_CURRENT_WEEK(state, payload) {
            state.week = new Week(payload);
        }
    },

    actions: {
        setCurrentWeek({commit}, payload) {
            commit('SET_WEEK', payload)
        },
    }
};