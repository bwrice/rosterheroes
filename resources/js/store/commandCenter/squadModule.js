import * as squadApi from '../../api/squadApi';
import * as measurableApi from '../../api/measurableApi';
import * as heroApi from '../../api/heroApi';
import Hero from "../../models/Hero";
import Measurable from "../../models/Measurable";
import MobileStorage from "../../models/MobileStorage";
import SlotTransaction from "../../models/SlotTransaction";
import Squad from "../../models/Squad";

export default {

    state: {
        squad: new Squad({}),
        heroes: [],
        mobileStorage: new MobileStorage({}),
        barracksLoading: true,
    },

    getters: {
        _squad(state) {
            return state.squad;
        },
        _heroes(state) {
            return state.heroes;
        },
        _mobileStorage(state) {
            return state.mobileStorage;
        },
        _barracksLoading(state) {
            return state.barracksLoading;
        },
        _focusedHero: (state) => (route) => {
            let hero = state.heroes.find(hero => hero.slug === route.params.heroSlug);
            return hero ? hero : new Hero({});
        },
        _squadHighMeasurable: (state) => (measurableTypeName) => {
            let measurableAmounts = state.heroes.map(function (hero) {
                return hero.getMeasurableByType(measurableTypeName).buffedAmount;
            });
            return measurableAmounts.reduce(function(amountA, amountB) {
                return Math.max(amountA, amountB);
            }, 0);
        },
        _availableSpiritEssence(state) {
            let essenceUsed = state.heroes.reduce(function (essence, hero) {
                if (hero.playerSpirit) {
                    return essence + hero.playerSpirit.essenceCost;
                }
                return essence;
            }, 0);
            return state.squad.spiritEssence - essenceUsed;
        }
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.squad = payload;
        },
        SET_HEROES(state, payload) {
            state.heroes = payload;
        },
        SET_MOBILE_STORAGE(state, payload) {
            state.mobileStorage = payload;
        },
        SET_BARRACKS_LOADING(state, payload) {
            state.barracksLoading = payload;
        },
    },

    actions: {

        async updateSquad({commit}, route) {
            let squadResponse = await squadApi.getSquad(route.params.squadSlug);
            commit('SET_SQUAD', new Squad(squadResponse.data));
        },

        async updateBarracks({commit, dispatch}, route) {
            // TODO separate out API requests break "barracks loading" into separate props for heroes/wagon
            let squadSlug = route.params.squadSlug;
            let heroesResponse = await squadApi.getHeroes(squadSlug);
            let heroes = heroesResponse.data.map(function (hero) {
                return new Hero(hero);
            });
            commit('SET_HEROES', heroes);
            let mobileStorageResponse = await squadApi.getMobileStorage(squadSlug);
            commit('SET_MOBILE_STORAGE', new MobileStorage(mobileStorageResponse));
            commit('SET_BARRACKS_LOADING', false);
        },

        async raiseHeroMeasurable({state, commit, dispatch}, payload) {

            try {
                let measurableResponse = await measurableApi.raise(payload.measurableUuid, payload.raiseAmount);
                let updatedMeasurable = new Measurable(measurableResponse);
                let updatedHeroes = _.cloneDeep(state.heroes);
                let matchingHero = updatedHeroes.find(function (hero) {
                    return payload.heroSlug === hero.slug;
                });

                let measurableIndex = matchingHero.measurables.findIndex(function (measurable) {
                    return measurable.uuid === updatedMeasurable.uuid;
                });

                matchingHero.measurables.splice(measurableIndex, 1, updatedMeasurable);

                commit('SET_HEROES', updatedHeroes);

                let text = updatedMeasurable.measurableType.name.toUpperCase() + ' raised to ' + updatedMeasurable.buffedAmount;
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch(e) {
                dispatch('snackBarError', {});
            }
        },

        async emptyHeroSlot({state, commit, dispatch}, {heroSlug, slotUuid}) {

            try {
                let transactionResponse = await heroApi.emptySlot(heroSlug, slotUuid);
                let slotTransactions = transactionResponse.map(function (transaction) {
                    return new SlotTransaction(transaction);
                });
                slotTransactions.forEach(function (slotTransaction) {
                    slotTransaction.syncSlots({
                        state,
                        commit,
                        dispatch
                    })
                })
            } catch (e) {
                console.log(e);
                dispatch('snackBarError', {});
            }
        },

        async equipHeroSlotFromWagon({state, commit, dispatch}, {heroSlug, slotUuid, itemUuid}) {

            try {
                let transactionResponse = await heroApi.equipFromWagon({
                    heroSlug,
                    slotUuid,
                    itemUuid
                });
                let slotTransactions = transactionResponse.map(function (transaction) {
                    return new SlotTransaction(transaction);
                });
                slotTransactions.forEach(function (slotTransaction) {
                    slotTransaction.syncSlots({
                        state,
                        commit,
                        dispatch
                    })
                })
            } catch (e) {
                console.log(e);
                dispatch('snackBarError', {})
            }
        },

        async addSpiritToHero({state, commit, dispatch}, payload) {

            try {

                let heroResponse = await heroApi.addSpirit(payload.heroSlug, payload.spiritUuid);
                let updatedHero = new Hero(heroResponse.data);
                let heroes = _.cloneDeep(state.heroes);

                let index = heroes.findIndex(function (hero) {
                    return hero.uuid === updatedHero.uuid;
                });

                if (index !== -1) {
                    heroes.splice(index, 1, updatedHero);
                    commit('SET_HEROES', heroes);
                    dispatch('snackBarSuccess', {
                        text: updatedHero.playerSpirit.player.fullName + ' now embodies ' + updatedHero.name,
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

                let heroResponse = await heroApi.removeSpirit(payload.heroSlug, payload.spiritUuid);
                let updatedHero = new Hero(heroResponse.data);
                let heroes = _.cloneDeep(state.heroes);

                let index = heroes.findIndex(function (hero) {
                    return hero.uuid === updatedHero.uuid;
                });

                console.log("Here");
                if (index !== -1) {
                    heroes.splice(index, 1, updatedHero);
                    commit('SET_HEROES', heroes);
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
            console.log("Here Again");
        }
    },
};
