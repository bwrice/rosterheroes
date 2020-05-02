import * as realmApi from '../../api/realmApi';
import Province from "../../models/Province";
import Territory from "../../models/Territory";
import Continent from "../../models/Continent";

export default {

    state: {
        provinces: [],
        territories: [],
        continents: []
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
        }
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
        }
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
        }
    }
};
