import * as weekApi from '../../api/weekApi';
import * as squadApi from '../../api/squadApi';
import * as heroApi from '../../api/heroApi';

export default {

    state: {
        playerSpiritsPool: [],
        rosterHeroes: []
    },

    getters: {
        _playerSpiritsPool(state) {
            return state.playerSpiritsPool;
        },
        _rosterHeroes(state) {
            return state.rosterHeroes;
        }
    },
    mutations: {
        SET_PLAYER_SPIRITS_POOL(state, payload) {
            state.playerSpiritsPool = payload;
        },
        SET_ROSTER_HEROES(state, payload) {
            state.rosterHeroes = payload;
        }
    },

    actions: {
        async updateRoster({commit, dispatch}, route) {

            let heroes = await squadApi.getRosterHeroes(route.params.squadSlug);
            commit('SET_ROSTER_HEROES', heroes);

            let playerSpirits = await weekApi.getCurrentPlayerSpirits();
            commit('SET_PLAYER_SPIRITS_POOL', playerSpirits);
        },

        async addSpiritToHero({state, commit, dispatch}, payload) {

            try {

                let updatedHero = await heroApi.addSpirit(payload.heroSlug, payload.spiritUuid);
                let rosterHeroes = _.cloneDeep(state.rosterHeroes);

                let index = rosterHeroes.findIndex(function (hero) {
                    return hero.uuid === updatedHero.uuid;
                });

                if (index !== -1) {
                    rosterHeroes.splice(index, 1, updatedHero);
                    commit('SET_ROSTER_HEROES', rosterHeroes);
                    dispatch('snackBarSuccess', {
                        text: updatedHero.playerSpirit.player.full_name + ' now embodies ' + updatedHero.name,
                        timeout: 3000
                    })
                } else {
                    console.warn("Didn't update roster heroes when adding spirit");
                }

            } catch (e) {
                let errors = e.response.data.errors;
                let snackBarPayload = {};
                if (errors && errors.roster) {
                    snackBarPayload = {
                        text: errors.roster[0]
                    }
                }
                dispatch('snackBarError', snackBarPayload)
            }
        },

        async removeSpiritFromHero({state, commit, dispatch}, payload) {
            try {

                let updatedHero = await heroApi.removeSpirit(payload.heroSlug, payload.spiritUuid);
                let rosterHeroes = _.cloneDeep(state.rosterHeroes);

                let index = rosterHeroes.findIndex(function (hero) {
                    return hero.uuid === updatedHero.uuid;
                });

                if (index !== -1) {
                    rosterHeroes.splice(index, 1, updatedHero);
                    commit('SET_ROSTER_HEROES', rosterHeroes);
                    dispatch('snackBarSuccess', {
                        text: updatedHero.name + ' saved',
                        timeout: 1500
                    })
                } else {
                    console.warn("Didn't update roster heroes when removing spirit");
                }

            } catch (e) {
                let errors = e.response.data.errors;
                let snackBarPayload = {};
                if (errors && errors.roster) {

                    console.log(errors.roster[0]);
                    snackBarPayload = {
                        text: errors.roster[0]
                    }
                }
                dispatch('snackBarError', snackBarPayload)
            }
        }
    }
};
