import Province from "../../models/Province";
import Territory from "../../models/Territory";
import Continent from "../../models/Continent";

export default {

    state: {
        provinces: [],
        territories: [],
        continents: [],
        continent: {
            'name': '',
            'realm_view_box': {
                'pan_x': 0,
                'pan_y': 0,
                'zoom_x': 315,
                'zoom_y': 240
            }
        },
        territory: {
            'name': '',
            'realm_view_box': {
                'pan_x': 0,
                'pan_y': 0,
                'zoom_x': 315,
                'zoom_y': 240
            }
        },
        realmMapMode: 'continent',
        province: {
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
        _continent(state) {
            return state.continent;
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
        async setMap({commit}, route) {

            let provinces = await Province.$get();
            commit('UPDATE_PROVINCES', provinces);
            let territories = await Territory.$get();
            commit('UPDATE_TERRITORIES', territories);
            let continents = await Continent.$get();
            commit('UPDATE_CONTINENTS', continents);

            if (route.params.continentSlug) {
                continents.forEach(function (continent) {
                    if (continent.slug === route.params.continentSlug) {
                        commit('SET_CONTINENT', continent);
                    }
                });
            } else if (route.params.territorySlug) {
                territories.forEach(function (territory) {
                    if (territory.slug === route.params.territorySlug) {
                        commit('SET_TERRITORY', territory);
                    }
                });
            }
        },
        async setProvince({commit}, payload) {
            commit('SET_PROVINCE', payload);
            let province = new Province(payload);
            let borders = await Province.custom(province, 'borders').$get();
            commit('SET_BORDERS', borders);
        },
        updateTerritory({commit}, payload) {
            commit('SET_TERRITORY', payload)
        },
        updateContinent({commit}, payload) {
            commit('SET_CONTINENT', payload)
        },
        setRealmMapMode({commit}, payload) {
            commit('SET_REALM_MAP_MODE', payload)
        },
    }
};
