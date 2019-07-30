import Week from "../../models/Week";

export default {

    state: {
        playerSpiritsPool: []
    },

    getters: {
        _playerSpiritsPool(state) {
            return state.playerSpiritsPool;
        }
    },
    mutations: {
        SET_PLAYER_SPIRITS_POOL(state, payload) {
            state.playerSpiritsPool = payload;
        }
    },

    actions: {
        async updatePlayerSpiritsPool({state, commit, rootState}) {

            let week = rootState.weekModule.week;
            let hero = rootState.heroModule.hero;

            if (week && hero) {
                let currentWeek = new Week(week);
                let playerSpirits = await currentWeek.playerSpirits().where('hero-race', hero.heroRace.name).$get();
                commit('SET_PLAYER_SPIRITS_POOL', playerSpirits)
            }
        }
    }
};