
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
            let {
                timeout = 1500,
                mode = '',
                text = 'Success!',
                color = 'success'
            } = payload;

            let trigger = 1;

            commit('SET_SNACKBAR', {
                timeout,
                color,
                mode,
                text,
                trigger
            });
        },
        snackBarError({commit}, payload) {

            console.log(payload);

            let {
                timeout = 4000,
                mode = '',
                text = 'Oops. Something went wrong',
                color = 'error'
            } = payload;

            let trigger = 1;

            commit('SET_SNACKBAR', {
                timeout,
                color,
                mode,
                text,
                trigger
            });
        }
    }
};
