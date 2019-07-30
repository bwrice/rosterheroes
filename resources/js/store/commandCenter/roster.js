import Week from "../../models/Week";

export default {

    state: {
        rosterFocusedHero: null,
        playerSpiritsPool: []
    },

    getters: {
        _rosterFocusedHero(state) {
            return state.rosterFocusedHero;
        },
        _playerSpiritsPool(state) {
            return state.playerSpiritsPool;
        }
    },
    mutations: {
        SET_ROSTER_FOCUSED_HERO(state, payload) {
            state.rosterFocusedHero = payload;
        },
        SET_PLAYER_SPIRITS_POOL(state, payload) {
            state.playerSpiritsPool = payload;
        }
    },

    actions: {
        setRosterFocusedHero({commit}, payload) {
            commit('SET_ROSTER_FOCUSED_HERO', payload)
        },
        async updatePlayerSpiritsPool({state, commit, rootState}) {

            let week = rootState.weekModule.week;
            let hero = rootState.heroModule.hero;
            if (week && hero) {
                console.log("HERE");

                let currentWeek = new Week(week);
                let playerSpirits = await currentWeek.playerSpirits().where('hero-race', hero.heroRace.name).$get();
                commit('SET_PLAYER_SPIRITS_POOL', playerSpirits)
            }
        }
    }
};