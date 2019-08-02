import Province from "../../models/Province";
import Territory from "../../models/Territory";
import Continent from "../../models/Continent";

export default {

    state: {
        provinces: [],
        territories: [],
        continents: [],
        continent: null
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
        _continent(state) {
            return state.continent;
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
        SET_CONTINENT(state, payload) {
            state.continent = payload;
        },
    },

    actions: {
        async setMap({commit}) {
            let provinces = await Province.$get();
            commit('UPDATE_PROVINCES', provinces);
            let territories = await Territory.$get();
            commit('UPDATE_TERRITORIES', territories);
            let continents = await Continent.$get();
            commit('UPDATE_CONTINENTS', continents);
        },
        updateContinent({commit}, payload) {
            commit('SET_CONTINENT', payload)
        }
    }
};