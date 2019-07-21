
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
        setPlayerSpiritsPool({commit}, payload) {
            commit('SET_PLAYER_SPIRITS_POOL', payload)
        }
    }
};