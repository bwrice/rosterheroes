
export default {

    state: {
        snackBar: {
            trigger: false,
            color: '',
            mode: '',
            timeout: 2000,
            text: ''
        }
    },

    getters: {
        _snackBar(state) {
            return state.snackBar;
        }
    },
    mutations: {
        SET_SNACKBAR(state, payload) {
            state.snackBar = payload;
        }
    },

    actions: {
        setSnackBar({commit}, payload) {
            commit('SET_SNACKBAR', payload)
        }
    }
};