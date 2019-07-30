
export default {

    state: {
        hero: null
    },

    getters: {
        _hero(state) {
            return state.hero;
        }
    },
    mutations: {
        SET_HERO(state, payload) {
            state.hero = payload;
        },
    },
    actions: {
        updateHero({commit}, payload) {
            commit('SET_HERO', payload)
        }
    }
};