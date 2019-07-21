import Week from '../../models/Week';

export default {

    state: {
        week: new Week({})
    },

    getters: {
        _week(state) {
            return state.week;
        }
    },
    mutations: {
        SET_WEEK(state, payload) {
            state.week = new Week(payload);
        }
    },

    actions: {
        setRosterFocusedHero({commit}, payload) {
            commit('SET_WEEK', payload)
        },
    }
};