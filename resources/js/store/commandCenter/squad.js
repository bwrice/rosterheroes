import Squad from '../../models/Squad'

export default {

    state: {
        current: new Squad({}),
        original: null
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
            state.current = new Squad(payload);
            state.original = new Squad(payload);
        }
    },

    actions: {
        setSquad({commit}, payload) {
            commit('SET_SQUAD', payload)
        },
    }
};