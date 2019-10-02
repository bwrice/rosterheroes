import * as squadApi from '../../api/squadApi';
import * as measurableApi from '../../api/measurableApi';
import * as heroApi from '../../api/heroApi';
import BarracksHero from "../../models/BarracksHero";
import Measurable from "../../models/Measurable";
import MobileStorage from "../../models/MobileStorage";
import SlotTransaction from "../../models/SlotTransaction";

export default {

    state: {
        barracksHeroes: [],
        mobileStorage: new MobileStorage({})
    },

    getters: {
        _barracksHeroes(state) {
            return state.barracksHeroes;
        },
        _mobileStorage(state) {
            return state.mobileStorage;
        }
    },
    mutations: {
        SET_BARRACKS_HEROES(state, payload) {
            state.barracksHeroes = payload;
        },
        SET_MOBILE_STORAGE(state, payload) {
            state.mobileStorage = payload;
        },
    },

    actions: {
        async updateBarracks({commit, dispatch}, route) {
            let squadSlug = route.params.squadSlug;
            let heroesResponse = await squadApi.getBarracksHeroes(squadSlug);
            let heroes = heroesResponse.map(function (hero) {
                return new BarracksHero(hero);
            });
            commit('SET_BARRACKS_HEROES', heroes);
            let mobileStorageResponse = await squadApi.getMobileStorage(squadSlug);
            commit('SET_MOBILE_STORAGE', new MobileStorage(mobileStorageResponse));
        },

        async raiseHeroMeasurable({state, commit, dispatch}, payload) {

            try {
                let measurableResponse = await measurableApi.raise(payload.measurableUuid, payload.raiseAmount);
                let updatedMeasurable = new Measurable(measurableResponse);
                let updatedHeroes = _.cloneDeep(state.barracksHeroes);
                let matchingHero = updatedHeroes.find(function (hero) {
                    return payload.heroSlug === hero.slug;
                });

                let measurableIndex = matchingHero.measurables.findIndex(function (measurable) {
                    return measurable.uuid === updatedMeasurable.uuid;
                });

                matchingHero.measurables.splice(measurableIndex, 1, updatedMeasurable);

                commit('SET_BARRACKS_HEROES', updatedHeroes);

                let text = updatedMeasurable.measurableType.name.toUpperCase() + ' raised to ' + updatedMeasurable.currentAmount;
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
        }
    },
};
