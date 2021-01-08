import * as realmApi from '../../api/realmApi';
import * as helpers from '../../helpers/vuexHelpers';
import Province from "../../models/Province";
import Territory from "../../models/Territory";
import Continent from "../../models/Continent";
import MapProvince from "../../models/MapProvince";
import * as provinceEventApi from "../../api/provinceEventApi";
import ProvinceEvent from "../../models/ProvinceEvent";

export default {

    state: {
        provinces: [],
        territories: [],
        continents: [],
        mapProvinces: [],
        globalProvinceEvents: []
    },

    getters: {
        _provinces(state) {
            return state.provinces;
        },
        _territories(state) {
            return state.territories;
        },
        _continents(state) {
            return state.continents;
        },
        _provincesByUuids: (state) => (uuids) => {
            return state.provinces.filter(province => uuids.includes(province.uuid));
        },
        _provincesByContinentID: (state) => (continentID) => {
            return state.provinces.filter(province => province.continentID === continentID);
        },
        _provincesByTerritoryID: (state) => (territoryID) => {
            return state.provinces.filter(province => province.territoryID === territoryID);
        },
        _continentByID: (state) => (continentID) => {
            let continent = state.continents.find(continent => continent.id === continentID);
            return continent ? continent : new Continent({});
        },
        _continentBySlug: (state) => (slug) => {
            let continent = state.continents.find(continent => continent.slug === slug);
            return continent ? continent : new Continent({});
        },
        _territoryByID: (state) => (territoryID) => {
            let territory = state.territories.find(territory => territory.id === territoryID);
            return territory ? territory : new Territory({});
        },
        _territoryBySlug: (state) => (slug) => {
            let territory = state.territories.find(territory => territory.slug === slug);
            return territory ? territory : new Territory({});
        },
        _provinceBySlug: (state) => (slug) => {
            let province = state.provinces.find(province => province.slug === slug);
            return province ? province : new Province({});
        },
        _provinceByUuid: (state) => (uuid) => {
            let province = state.provinces.find(province => province.uuid === uuid);
            return province ? province : new Province({});
        },
        _mapProvinceByProvinceSlug: (state) => (provinceSlug) =>  {
            return state.mapProvinces.find(mapProvince => mapProvince.provinceSlug === provinceSlug);
        },
        _globalProvinceEvents(state) {
            return state.globalProvinceEvents;
        },
    },
    mutations: {
        SET_PROVINCES(state, payload) {
            state.provinces = payload;
        },
        SET_TERRITORIES(state, payload) {
            state.territories = payload;
        },
        SET_CONTINENTS(state, payload) {
            state.continents = payload;
        },
        SET_REALM_LOADING(state, payload) {
            state.loading = payload;
        },
        REPLACE_UPDATED_MAP_PROVINCE(state, updatedMapProvince) {
            state.mapProvinces = helpers.replaceOrPushElement(state.mapProvinces, updatedMapProvince, 'provinceUuid')
        },
        SET_GLOBAL_PROVINCE_EVENTS(state, globalProvinceEvents) {
            state.globalProvinceEvents = globalProvinceEvents;
        },
    },

    actions: {
        async updateProvinces({commit}) {
            try {
                let provinceResponse = await realmApi.getProvinces();
                let provinces = provinceResponse.data.map(function (province) {
                    return new Province(province);
                });
                commit('SET_PROVINCES', provinces);
            } catch (e) {
                console.warn("Failed to update provinces");
            }
        },
        async updateTerritories({commit}) {
            try {
                let territoriesResponse = await realmApi.getTerritories();
                let territories = territoriesResponse.data.map(function (territory) {
                    return new Territory(territory);
                });
                commit('SET_TERRITORIES', territories);
            } catch (e) {
                console.warn("Failed to update territories");
            }
        },
        async updateContinents({commit}) {
            try {
                let continentsResponse = await realmApi.getContinents();
                let continents = continentsResponse.data.map(function (continent) {
                    return new Continent(continent);
                });
                commit('SET_CONTINENTS', continents);
            } catch (e) {
                console.warn("Failed to update continents");
            }
        },

        async updateMapProvince({state, commit, dispatch}, provinceSlug) {

            let response = await realmApi.getMapProvince(provinceSlug);
            let mapProvince = new MapProvince(response.data);
            commit('REPLACE_UPDATED_MAP_PROVINCE', mapProvince);
        },

        async updateGlobalEvents({commit}) {
            try {
                let response = await provinceEventApi.getGlobalEvents();
                let provinceEvents = response.data.map(provinceEvent => new ProvinceEvent(provinceEvent));
                commit('SET_GLOBAL_PROVINCE_EVENTS', provinceEvents)
            } catch (e) {
                console.warn("Failed to update global events");
            }
        },

        async handleNewGlobalProvinceEvent({commit, state}, eventUuid) {

            console.log("GLOBAL EVENT", eventUuid);
            let response = await provinceEventApi.getProvinceEvent(eventUuid);
            let provinceEvent = new ProvinceEvent(response.data.provinceEvent);
            let globalProvinceEvents = _.cloneDeep(state.globalProvinceEvents);
            let index = globalProvinceEvents.findIndex(pEvent => pEvent.uuid === provinceEvent.uuid);
            if (index !== -1) {
                globalProvinceEvents[index] = provinceEvent;
            } else {
                globalProvinceEvents.unshift(provinceEvent);
            }
            commit('SET_GLOBAL_PROVINCE_EVENTS', globalProvinceEvents);
        }
    }
};
