import * as weekApi from '../../api/weekApi';

export default {

    state: {
        playerSpiritsPool: [],
        rosterHeroes: [],
        rosterFocusedHero: {
            name: '',
            playerSpirit: null
        }
    },

    getters: {
        _playerSpiritsPool(state) {
            return state.playerSpiritsPool;
        },
        _rosterHeroes(state) {
            return state.rosterHeroes;
        },
        _rosterFocusedHero(state) {
            return state.rosterFocusedHero;
        }
    },
    mutations: {
        SET_PLAYER_SPIRITS_POOL(state, payload) {
            state.playerSpiritsPool = payload;
        }
    },

    actions: {
        async updateRoster({commit}, route) {

            let playerSpirits = await weekApi.getCurrentPlayerSpirits();
            commit('SET_PLAYER_SPIRITS_POOL', playerSpirits);

            // if (week && hero) {
            //     let currentWeek = new Week(week);
            //     let playerSpirits = await currentWeek.playerSpirits().where('hero-race', hero.heroRace.name).$get();
            //     commit('SET_PLAYER_SPIRITS_POOL', playerSpirits)
            // }
        }
    }
};
