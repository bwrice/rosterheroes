import Province from "../../models/Province";
import Territory from "../../models/Territory";
import Continent from "../../models/Continent";

export default {

    state: {
        provinces: [],
        territories: [],
        continents: [],
        territory: {
            'name': '',
            'slug': null,
            'realm_view_box': {
                'pan_x': 0,
                'pan_y': 0,
                'zoom_x': 315,
                'zoom_y': 240
            }
        },
        realmMapMode: 'continent',
        province: {
            'slug': null,
            view_box: {
                'pan_x': 0,
                'pan_y': 0,
                'zoom_x': 315,
                'zoom_y': 240,
            }
        },
        borders: []
    },

    getters: {
        _provinces(state) {
            return state.provinces;
        },
        _territories(state) {
            return state.territories;
        },
        _territory(state) {
            return state.territory;
        },
        _continents(state) {
            return state.continents;
        },
        _realmMapMode(state) {
            return state.realmMapMode;
        },
        _province(state) {
            return state.province;
        },
        _borders(state) {
            return state.borders;
        },
        _getProvinceBySlug(state, slug) {
            let slugProvince = null;
            state.provinces.forEach(function (province) {
                if (province.slug === slug) {
                    slugProvince = province;
                }
            });
            return slugProvince;
        }
    },
    mutations: {
        UPDATE_PROVINCES(state, payload) {
            state.provinces = payload;
        },
        UPDATE_TERRITORIES(state, payload) {
            state.territories = payload;
        },
        SET_TERRITORY(state, payload) {
            state.territory = payload;
        },
        UPDATE_CONTINENTS(state, payload) {
            state.continents = payload;
        },
        SET_CONTINENT(state, payload) {
            state.continent = payload;
        },
        SET_REALM_MAP_MODE(state, payload) {
            state.realmMapMode = payload;
        },
        SET_PROVINCE(state, payload) {
            state.province = payload;
        },
        SET_BORDERS(state, payload) {
            state.borders = payload;
        },
    },

    actions: {
        async setExploreMap({commit, dispatch}, route) {

            let provinces = await Province.$get();
            commit('UPDATE_PROVINCES', provinces);
            let territories = await Territory.$get();
            commit('UPDATE_TERRITORIES', territories);
            let continents = await Continent.$get();
            commit('UPDATE_CONTINENTS', continents);

            if (route.params.territorySlug) {
                dispatch('setTerritoryBySlug', route.params.territorySlug);
            } else if(route.params.provinceSlug) {
                dispatch('setProvinceBySlug', route.params.provinceSlug);
            }
        },
        async setProvince({commit}, payload) {
            commit('SET_PROVINCE', payload);
            let province = new Province(payload);
            let borders = await Province.custom(province, 'borders').$get();
            commit('SET_BORDERS', borders);
        },
        setProvinceBySlug({state, commit, dispatch}, slug) {
            state.provinces.forEach(function (province) {
                if (province.slug === slug) {
                    dispatch('setProvince', province);
                }
            });
        },
        updateTerritory({commit}, payload) {
            commit('SET_TERRITORY', payload)
        },
        setTerritoryBySlug({state, commit}, slug) {
            state.territories.forEach(function (territory) {
                if (territory.slug === slug) {
                    commit('SET_TERRITORY', territory);
                }
            });
        },
        updateContinent({commit}, payload) {
            commit('SET_CONTINENT', payload)
        },
        setContinentBySlug({state, commit}, slug) {
            state.continents.forEach(function (continent) {
                if (continent.slug === slug) {
                    commit('SET_CONTINENT', continent);
                }
            });
        },
        setRealmMapMode({commit}, payload) {
            commit('SET_REALM_MAP_MODE', payload)
        },
    }
};
