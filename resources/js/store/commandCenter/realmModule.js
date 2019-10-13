import * as realmApi from '../../api/realmApi';
import Province from "../../models/Province";
import Territory from "../../models/Territory";
import Continent from "../../models/Continent";

export default {

    state: {
        provinces: [],
        territories: [],
        continents: [],
        realmMapMode: 'continent',
        borders: [],
        loading: false
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
        _realmMapMode(state) {
            return state.realmMapMode;
        },
        _realmLoading(state) {
            return state.loading;
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
        SET_REALM_MAP_MODE(state, payload) {
            state.realmMapMode = payload;
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
                let continentsResponse = await realmApi.getProvinces();
                let continents = continentsResponse.data.map(function (continent) {
                    return new Continent(continent);
                });
                commit('SET_CONTINENTS', continents);
            } catch (e) {
                console.warn("Failed to update continents");
            }
        },
        // async setRealm({state, commit}) {
        //     commit('SET_REALM_LOADING', true);
        //     let provinces = await ProvinceApiModel.$get();
        //     commit('UPDATE_PROVINCES', provinces);
        //     let territories = await TerritoryApiModel.$get();
        //     commit('UPDATE_TERRITORIES', territories);
        //     let continents = await ContinentApiModel.$get();
        //     commit('UPDATE_CONTINENTS', continents);
        //     commit('SET_REALM_LOADING', false);
        // },
        setRealmMapMode({commit}, payload) {
            commit('SET_REALM_MAP_MODE', payload)
        },
    }
};
