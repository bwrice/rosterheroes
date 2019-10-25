import * as squadApi from '../../api/squadApi';
import Province from "../../models/Province";

export default {

    state: {
        quests: [],
        province: new Province({})
    },

    getters: {
        _currentLocationQuests(state) {
            return state.quests;
        },
        _currentLocationProvince(state) {
            return state.province;
        },
    },
    mutations: {
        SET_CURRENT_LOCATION_QUESTS(state, payload) {
            state.quests = payload;
        },
        SET_CURRENT_LOCATION_PROVINCE(state, payload) {
            state.province = payload;
        }
    },

    actions: {
        // async updateCurrentLocationQuests({commit}, route) {
        //     let squadSlug = route.params.squadSlug;
        //     // let location = await squadApi.getCurrentLocation(squadSlug);
        //     commit('SET_CURRENT_LOCATION_QUESTS', location)
        // },
        async updateCurrentLocationProvince({commit}, route) {
            try {
                let response = await squadApi.getCurrentLocationProvince(route.params.squadSlug);
                let province = new Province(response.data);
                commit('SET_CURRENT_LOCATION_PROVINCE', province)
            } catch (e) {
                console.warn("Failed to update current location province");
            }
        },
        updateCurrentLocation({commit, dispatch}, route, alreadyUpdated = {}) {
            let {
                province,
            } = alreadyUpdated;
            province ? commit('SET_CURRENT_LOCATION_PROVINCE', province) : dispatch('updateCurrentLocationProvince', route);
        },
    }
};
