
export default {

    state: {
        currentLocation: {
            name: '',
            borders: []
        }
    },

    getters: {
        _currentLocation(state) {
            return state.currentLocation;
        }
    },
    mutations: {
        SET_CURRENT_LOCATION(state, payload) {
            state.currentLocation = payload;
        }
    },

    actions: {
        setCurrentLocation({commit}, payload) {
            commit('SET_CURRENT_LOCATION', payload)
        },
    }
};
