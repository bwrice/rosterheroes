import * as weekApi from '../../api/weekApi';

export default {

    state: {
        week: null,
        playerSpirits: [],
    },

    getters: {
        _currentWeek(state) {
            return state.week;
        },
        _playerSpirits(state) {
            return state.playerSpirits;
        }
    },
    mutations: {
        SET_CURRENT_WEEK(state, payload) {
            state.week = payload;
        },
        SET_PLAYER_SPIRITS(state, payload) {
            state.playerSpirits = payload;
        }
    },

    actions: {
        async updateCurrentWeek({commit}) {
            try {
                let weekResponse = await weekApi.getCurrentWeek();
                commit('SET_CURRENT_WEEK', weekResponse.data);
            } catch (e) {
                console.warn("Failed to update current week");
            }
        },
        async updatePlayerSpirits({commit}) {
            try {
                let playerSpiritsResponse = await weekApi.getCurrentPlayerSpirits();
                commit('SET_PLAYER_SPIRITS', playerSpiritsResponse.data);
            } catch (e) {
                console.warn("Failed to update player spirits");
            }
        }
    }
};
