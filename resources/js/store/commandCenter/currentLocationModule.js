import * as squadApi from '../../api/squadApi';
import * as provinceEventApi from '../../api/provinceEventApi';
import Province from "../../models/Province";
import Quest from "../../models/Quest";
import LocalStash from "../../models/LocalStash";
import LocalSquad from "../../models/LocalSquad";
import Merchant from "../../models/Merchant";
import Shop from "../../models/Shop";
import ProvinceEvent from "../../models/ProvinceEvent";

export default {

    state: {
        quests: [],
        province: new Province({}),
        localSquads: [],
        localMerchants: [],
        localProvinceEvents: []
    },

    getters: {
        _currentLocationQuests(state) {
            return state.quests;
        },
        _currentLocationProvince(state) {
            return state.province;
        },
        _localSquads(state) {
            return state.localSquads;
        },
        _localMerchants(state) {
            return state.localMerchants;
        },
        _localProvinceEvents(state) {
            return state.localProvinceEvents;
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
        SET_LOCAL_SQUADS(state, localSquads) {
            state.localSquads = localSquads;
        },
        SET_LOCAL_MERCHANTS(state, localMerchants) {
            state.localMerchants = localMerchants;
        },
        SET_LOCAL_PROVINCE_EVENTS(state, localProvinceEvents) {
            state.localProvinceEvents = localProvinceEvents;
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
        async updateLocalSquads({commit}, route) {
            try {
                let response = await squadApi.getCurrentLocationSquads(route.params.squadSlug);
                let localSquads = response.data.map(localSquad => new LocalSquad(localSquad));
                commit('SET_LOCAL_SQUADS', localSquads)
            } catch (e) {
                console.warn("Failed to update local squads");
            }
        },
        async updateLocalMerchants({commit}, route) {
            try {
                let response = await squadApi.getLocalMerchants(route.params.squadSlug);
                let localMerchants = response.data.map(merchant => new Merchant(merchant));
                commit('SET_LOCAL_MERCHANTS', localMerchants)
            } catch (e) {
                console.warn("Failed to update local merchants");
            }
        },
        async updateLocalProvinceEvents({commit}, route) {
            try {
                let response = await squadApi.getCurrentLocationProvinceEvents(route.params.squadSlug);
                let provinceEvents = response.data.map(provinceEvent => new ProvinceEvent(provinceEvent));
                commit('SET_LOCAL_PROVINCE_EVENTS', provinceEvents)
            } catch (e) {
                console.warn("Failed to update province events");
            }
        },
        updateCurrentLocation({commit, dispatch}, route, alreadyUpdated = {}) {

            let {
                province,
                quests,
                localStash,
                localSquads,
                localMerchants,
                localProvinceEvents
            } = alreadyUpdated;

            commit('SET_SHOP', new Shop({}));

            province ? commit('SET_CURRENT_LOCATION_PROVINCE', province) : dispatch('updateCurrentLocationProvince', route);
            quests ? commit('SET_CURRENT_LOCATION_QUESTS', quests) : dispatch('updateCurrentLocationQuests', route);
            localStash ? commit('SET_LOCAL_STASH', localStash) : dispatch('updateLocalStash', route);
            localSquads ? commit('SET_LOCAL_SQUADS', localStash) : dispatch('updateLocalSquads', route);
            localMerchants ? commit('SET_LOCAL_MERCHANTS', localMerchants) : dispatch('updateLocalMerchants', route);
            localProvinceEvents ? commit('SET_LOCAL_PROVINCE_EVENTS', localProvinceEvents) : dispatch('updateLocalProvinceEvents', route);
        },

        async handleProvinceEventCreated(store, eventUuid) {

            let response = await provinceEventApi.getProvinceEvent(eventUuid);

            switch (response.data.provinceEvent.eventType) {
                case 'squad-enters-province':
                    handleSquadEntersProvince(store, response.data);
                    break;
                case 'squad-leaves-province':
                    handleSquadLeavesProvince(store, response.data);
                    break;
                case 'squad-joins-quest':
                    pushLocalProvinceEvent(store, response.data.provinceEvent);
                    break;
            }
        }
    }
};

function handleSquadEntersProvince({commit, state}, {provinceEvent, localSquad}) {

    pushLocalProvinceEvent({commit, state}, provinceEvent);

    localSquad = new LocalSquad(localSquad);
    let localSquads = _.cloneDeep(state.localSquads);
    let index = localSquads.findIndex(squad => squad.uuid === localSquad.uuid);
    if (index !== -1) {
        localSquads[index] = localSquad;
    } else {
        localSquads.unshift(localSquad);
    }
    commit('SET_LOCAL_SQUADS', localSquads);
}

function handleSquadLeavesProvince({commit, state}, {provinceEvent}) {

    pushLocalProvinceEvent({commit, state}, provinceEvent);

    let localSquads = state.localSquads.filter(squad => squad.uuid !== provinceEvent.squad.uuid);
    commit('SET_LOCAL_SQUADS', localSquads);
}

function pushLocalProvinceEvent({commit, state}, provinceEvent) {
    provinceEvent = new ProvinceEvent(provinceEvent);
    let localProvinceEvents = _.cloneDeep(state.localProvinceEvents);
    let index = localProvinceEvents.findIndex(pEvent => pEvent.uuid === provinceEvent.uuid);
    if (index !== -1) {
        localProvinceEvents[index] = provinceEvent;
    } else {
        localProvinceEvents.unshift(provinceEvent);
    }
    commit('SET_LOCAL_PROVINCE_EVENTS', localProvinceEvents);
}
