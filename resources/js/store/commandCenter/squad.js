export default {

    state: {
        squad: {
            name: ''
        }
    },

    getters: {
        _squad(state) {
            return state.squad;
        },
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.squad = payload;
        }
    },

    actions: {
        setSquad({commit}, payload) {
            commit('SET_SQUAD', payload)
        },
    }
};