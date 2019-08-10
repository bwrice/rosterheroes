
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
        },
        STOP_OVERLAY(state) {
            state.overlay = {
                show: false,
                message: null
            }
        }
    },

    actions: {
        setOverlay({commit}, payload) {
            commit('SET_OVERLAY', payload);
        },
        stopOverlay({commit}) {
            commit('STOP_OVERLAY');
        }
    }
};
