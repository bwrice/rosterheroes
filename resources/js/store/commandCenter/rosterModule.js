import * as weekApi from '../../api/weekApi';
import * as squadApi from '../../api/squadApi';
import * as heroApi from '../../api/heroApi';

export default {

    state: {
        playerSpiritsPool: [],
        loading: false,
        rosterHeroes: []
    },

    getters: {
        _playerSpiritsPool(state) {
            return state.playerSpiritsPool;
        },
        _rosterHeroes(state) {
            return state.rosterHeroes;
        },
        _rosterLoading(state) {
            return state.loading;
        }
    },
    mutations: {
        SET_PLAYER_SPIRITS_POOL(state, payload) {
            state.playerSpiritsPool = payload;
        },
        SET_ROSTER_HEROES(state, payload) {
            state.rosterHeroes = payload;
        },
        SET_ROSTER_LOADING(state, payload) {
            state.loading = payload;
        }
    },

    actions: {
        async updateRoster({commit}, route) {

            commit('SET_ROSTER_LOADING', true);

            // let heroes = await squadApi.getRosterHeroes(route.params.squadSlug);
            // commit('SET_ROSTER_HEROES', heroes);

            // let playerSpirits = await weekApi.getPlayerSpirits();
            // commit('SET_PLAYER_SPIRITS_POOL', playerSpirits);

            commit('SET_ROSTER_LOADING', false);
        },
    }
};
