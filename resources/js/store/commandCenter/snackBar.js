
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
        },
        SET_COLOR(state, payload) {
            state.snackBar.color = payload;
        },
        SET_TEXT(state, payload) {
            state.snackBar.text = payload;
        },
        SET_TIMEOUT(state, payload) {
            state.snackBar.timeout = payload;
        },
        TRIGGER(state) {
            state.snackBar.trigger++;
        }
    },

    actions: {
        setSnackBar({commit}, payload) {
            commit('SET_SNACKBAR', payload)
        },
        snackBarSuccess({commit}, payload) {
            commit('SET_TEXT', payload);
            commit('SET_COLOR', 'success');
            commit('SET_TIMEOUT', 1500);
            commit('TRIGGER');
        },
        snackBarError({commit}, payload) {
            commit('SET_TEXT', payload);
            commit('SET_COLOR', 'error');
            commit('SET_TIMEOUT', 5000);
            commit('TRIGGER');
        }
    }
};