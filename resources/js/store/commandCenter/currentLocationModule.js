import * as squadApi from '../../api/squadApi';
import Province from "../../models/Province";
import Quest from "../../models/Quest";

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
        _questBySlug: (state) => (slug) => {
            let quest = state.quests.find(quest => quest.slug === slug);
            return quest ? quest : new Quest({});
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
        async updateCurrentLocationProvince({commit}, route) {
            try {
                let response = await squadApi.getCurrentLocationProvince(route.params.squadSlug);
                let province = new Province(response.data);
                commit('SET_CURRENT_LOCATION_PROVINCE', province)
            } catch (e) {
                console.warn("Failed to update current location province");
            }
        },
        async updateCurrentLocationQuests({commit}, route) {
            try {
                let response = await squadApi.getCurrentLocationQuests(route.params.squadSlug);
                let quests = response.data.map(quest => new Quest(quest));
                commit('SET_CURRENT_LOCATION_QUESTS', quests)
            } catch (e) {
                console.warn("Failed to update current location quests");
            }
        },
        updateCurrentLocation({commit, dispatch}, route, alreadyUpdated = {}) {

            let {
                province,
                quests,
            } = alreadyUpdated;

            province ? commit('SET_CURRENT_LOCATION_PROVINCE', province) : dispatch('updateCurrentLocationProvince', route);
            quests ? commit('SET_CURRENT_LOCATION_QUESTS', quests) : dispatch('updateCurrentLocationQuests', route);
        },
    }
};
