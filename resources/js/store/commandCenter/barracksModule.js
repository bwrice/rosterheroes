import * as squadApi from '../../api/squadApi';

export default {

    state: {
        barracksHeroes: []
    },

    getters: {
        _barracksHeroes(state) {
            return state.barracksHeroes;
        }
    },
    mutations: {
        SET_BARRACKS_HEROES(state, payload) {
            state.barracksHeroes = payload;
        }
    },

    actions: {
        async updateBarracksHeroes({commit}, squadSlug) {
            let heroes = await squadApi.getBarracksHeroes(squadSlug);
            commit('SET_BARRACKS_HEROES', heroes);
        }
    }
};
