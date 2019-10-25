import * as squadApi from '../../api/squadApi';

export default {

    state: {
        quests: []
    },

    getters: {
        _currentLocationQuests(state) {
            return state.quests;
        }
    },
    mutations: {
        SET_CURRENT_LOCATION_QUESTS(state, payload) {
            state.quests = payload;
        }
    },

    actions: {
        async updateCurrentLocationQuests({commit}, route) {
            let squadSlug = route.params.squadSlug;
            let location = await squadApi.getCurrentLocation(squadSlug);
            commit('SET_CURRENT_LOCATION', location)
        },
        setCurrentLocation({commit}, location) {
            commit('SET_CURRENT_LOCATION', location);
        }
    }
};
