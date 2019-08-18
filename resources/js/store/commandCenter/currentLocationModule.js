import * as squadApi from '../../api/squadApi';

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
        async updateCurrentLocation({commit}, route) {
            let squadSlug = route.params.squadSlug;
            let location = await squadApi.getCurrentLocation(squadSlug);
            commit('SET_CURRENT_LOCATION', location)
        },
    }
};
