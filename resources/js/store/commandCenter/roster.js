
export default {

    state: {
        rosterFocusedHero: null
    },

    getters: {
        _rosterFocusedHero(state) {
            return state.rosterFocusedHero;
        }
    },
    mutations: {
        SET_FOCUSED_HERO(state, payload) {
            state.rosterFocusedHero = payload;
        }
    },

    actions: {
        setRosterFocusedHero({commit}, payload) {
            commit('SET_FOCUSED_HERO', payload)
        },
    }
};