import * as squadApi from '../../api/squadApi';
import Province from "../../models/Province";
import Quest from "../../models/Quest";
import LocalStash from "../../models/LocalStash";
import LocalSquad from "../../models/LocalSquad";

export default {

    state: {
        quests: [],
        province: new Province({}),
        localStash: new LocalStash({}),
        localSquads: [],
    },

    getters: {
        _currentLocationQuests(state) {
            return state.quests;
        },
        _currentLocationProvince(state) {
            return state.province;
        },
        _localStash(state) {
            return state.localStash;
        },
        _localSquads(state) {
            return state.localSquads;
        },
        _currentLocationQuestBySlug: (state) => (slug) => {
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
        },
        SET_LOCAL_STASH(state, payload) {
            state.localStash = payload;
        },
        SET_LOCAL_SQUADS(state, localSquads) {
            state.localSquads = localSquads;
        },
        ADD_ITEM_TO_LOCAL_STASH(state, payload) {
            state.localStash.items.push(payload);
        },
        REMOVE_ITEM_FROM_LOCAL_STASH(state, itemToRemove) {
            let localStash = _.cloneDeep(state.localStash);

            let index = localStash.items.findIndex(function (item) {
                return item.uuid === itemToRemove.uuid;
            });

            if (index !== -1) {
                localStash.items.splice(index, 1);
            }

            localStash.capacityUsed -= itemToRemove.weight;
            state.localStash = localStash;
        },
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
        async updateLocalSquads({commit}, route) {
            try {
                let response = await squadApi.getCurrentLocationSquads(route.params.squadSlug);
                let localSquads = response.data.map(localSquad => new LocalSquad(localSquad));
                commit('SET_LOCAL_SQUADS', localSquads)
            } catch (e) {
                console.warn("Failed to update local squads");
            }
        },
        async updateLocalStash({commit}, route) {
            try {
                let response = await squadApi.getLocalStash(route.params.squadSlug);
                let localStash = new LocalStash(response.data);
                commit('SET_LOCAL_STASH', localStash)
            } catch (e) {
                console.warn("Failed to update local stash");
            }
        },
        updateCurrentLocation({commit, dispatch}, route, alreadyUpdated = {}) {

            let {
                province,
                quests,
                localStash,
                localSquads,
            } = alreadyUpdated;

            province ? commit('SET_CURRENT_LOCATION_PROVINCE', province) : dispatch('updateCurrentLocationProvince', route);
            quests ? commit('SET_CURRENT_LOCATION_QUESTS', quests) : dispatch('updateCurrentLocationQuests', route);
            localStash ? commit('SET_LOCAL_STASH', localStash) : dispatch('updateLocalStash', route);
            localSquads ? commit('SET_LOCAL_SQUADS', localStash) : dispatch('updateLocalSquads', route);
        },
    }
};
