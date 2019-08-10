
export default {

    state: {
        overlay: {
            show: false,
            message: null
        }
    },

    getters: {
        _overlay(state) {
            return state.overlay;
        }
    },
    mutations: {
        SET_OVERLAY(state, payload) {
            state.overlay = payload;
        }
    },

    actions: {
        setOverlay({commit}, payload) {
            commit('SET_OVERLAY', payload)
        },
    }
};
