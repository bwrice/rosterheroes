
export default {

    state: {
        snackBar: {
            trigger: false,
            color: '',
            mode: '',
            timeout: 2000,
            text: ''
        },
        // Used to increment, because we just need a different number each time
        triggerCount: 0
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
    },

    actions: {
        setSnackBar({commit}, payload) {
            commit('SET_SNACKBAR', payload)
        },
        snackBarSuccess({state, commit}, payload) {

            let {
                timeout = 1500,
                mode = '',
                text = 'Success!',
                color = 'success'
            } = payload;

            let trigger = state.trigger++;

            commit('SET_SNACKBAR', {
                timeout,
                color,
                mode,
                text,
                trigger
            });
        },
        snackBarError({state, commit}, payload) {

            let {
                timeout = 4000,
                mode = '',
                text = 'Oops. Something went wrong',
                color = 'error'
            } = payload;

            let trigger = state.trigger++;

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
