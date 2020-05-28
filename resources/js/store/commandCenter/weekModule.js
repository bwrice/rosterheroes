import * as weekApi from '../../api/weekApi';
import PlayerSpirit from "../../models/PlayerSpirit";
import Game from "../../models/Game";
import Week from "../../models/Week";

export default {

    state: {
        week: new Week({}),
        playerSpirits: [],
        games: [],
        loadingSpirits: true
    },

    getters: {
        _loadingSpirits(state) {
            return state.loadingSpirits;
        },
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
        },
        _gameDescriptionByGameID: (state, getters) => (gameID) => {
            let game = getters._gameByID(gameID);
            let homeTeam = getters._teamByID(game.homeTeamID);
            let awayTeam = getters._teamByID(game.awayTeamID);
            return awayTeam.abbreviation + '@' + homeTeam.abbreviation;
        }
    },
    mutations: {
        SET_LOADING_SPIRITS(state, payload) {
            state.loadingSpirits = payload;
        },
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
                commit('SET_CURRENT_WEEK', new Week(weekResponse.data));
            } catch (e) {
                console.warn("Failed to update current week");
            }
        },
        async updatePlayerSpirits({commit}) {
            let playerSpirits = [];
            let retrieveSpirits = true;
            let offset = 0;
            let limit = 500;
            try {
                while (retrieveSpirits || offset > 20000) {
                    let playerSpiritsResponse = await weekApi.getPlayerSpirits('current', offset, limit);
                    let retrievedPlayerSpirits = playerSpiritsResponse.data.map(function (playerSpirit) {
                        return new PlayerSpirit(playerSpirit);
                    });
                    playerSpirits = _.union(playerSpirits, retrievedPlayerSpirits);
                    commit('SET_PLAYER_SPIRITS', playerSpirits);
                    retrieveSpirits = retrievedPlayerSpirits.length !== 0;
                    if (offset === 0) {
                        commit('SET_LOADING_SPIRITS', false);
                    }
                    offset += limit;
                }
            } catch (e) {
                console.warn("Failed to update player spirits");
                commit('SET_LOADING_SPIRITS', false);
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
