import Province from "../../models/Province";
import Territory from "../../models/Territory";
import Continent from "../../models/Continent";

export default {

    state: {
        provinces: [],
        territories: [],
        continents: [],
        realmMapMode: 'continent',
        borders: []
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
        }
    },
    mutations: {
        UPDATE_PROVINCES(state, payload) {
            state.provinces = payload;
        },
        UPDATE_TERRITORIES(state, payload) {
            state.territories = payload;
        },
        UPDATE_CONTINENTS(state, payload) {
            state.continents = payload;
        },
        SET_REALM_MAP_MODE(state, payload) {
            state.realmMapMode = payload;
        },
    },

    actions: {
        async setRealm({commit}) {
            let provinces = await Province.$get();
            commit('UPDATE_PROVINCES', provinces);
            let territories = await Territory.$get();
            commit('UPDATE_TERRITORIES', territories);
            let continents = await Continent.$get();
            commit('UPDATE_CONTINENTS', continents);
        },
        setRealmMapMode({commit}, payload) {
            commit('SET_REALM_MAP_MODE', payload)
        },
    }
};
