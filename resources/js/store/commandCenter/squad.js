
export default {

    state: {
        current: {},
        original: {}
    },

    getters: {
        _isDirty(state) {
            return ! _.isEqual(state.current, state.original);
        },
        _squad(state) {
            return state.current;
        },
        _availableSpiritEssence(state) {
            return state.current.availableSpiritEssence;
        }
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.current = payload;
            state.original = payload;
        }
    },

    actions: {
        setSquad({commit}, payload) {
            commit('SET_SQUAD', payload)
        },
    }
};