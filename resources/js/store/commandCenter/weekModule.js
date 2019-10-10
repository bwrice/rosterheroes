import * as weekApi from '../../api/weekApi';
import PlayerSpirit from "../../models/PlayerSpirit";
import Game from "../../models/Game";

export default {

    state: {
        week: null,
        playerSpirits: [],
        games: [],
    },

    getters: {
        _currentWeek(state) {
            return state.week;
        },
        _playerSpirits(state) {
            return state.playerSpirits;
        },
        _games(state) {
            return state.games;
        },
        _gameByID: (state) => (gameID) => {
            let game = state.games.find(function (game) {
                return game.id === gameID;
            });
            return game ? game : new Game({});
        }
    },
    mutations: {
        SET_CURRENT_WEEK(state, payload) {
            state.week = payload;
        },
        SET_PLAYER_SPIRITS(state, payload) {
            state.playerSpirits = payload;
        },
        SET_GAMES(state, payload) {
            state.games = payload;
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
                let playerSpiritsResponse = await weekApi.getPlayerSpirits('current');
                let playerSpirits = playerSpiritsResponse.data.map(function (playerSpirit) {
                    return new PlayerSpirit(playerSpirit);
                });
                commit('SET_PLAYER_SPIRITS', playerSpirits);
            } catch (e) {
                console.warn("Failed to update player spirits");
            }
        },
        async updateGames({commit}) {
            try {
                let gamesResponse = await weekApi.getGames('current');
                let games = gamesResponse.data.map(function (game) {
                    return new Game(game);
                });
                commit('SET_GAMES', games);
            } catch (e) {
                console.warn("Failed to update games");
            }
        }
    }
};
