export default {

    state: {
        continent: null
    },

    getters: {
        _continent(state) {
            return state.continent;
        }
    },
    mutations: {
        SET_CONTINENT(state, payload) {
            state.hero = payload;
        },
    },
    actions: {
        updateContinent({commit}, payload) {
            commit('SET_CONTINENT', payload)
        }
    }
};