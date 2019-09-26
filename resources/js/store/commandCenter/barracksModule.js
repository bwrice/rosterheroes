import * as squadApi from '../../api/squadApi';
import * as measurableApi from '../../api/measurableApi';
import * as heroApi from '../../api/heroApi';
import BarracksHero from "../../models/BarracksHero";
import Measurable from "../../models/Measurable";

export default {

    state: {
        barracksHeroes: []
    },

    getters: {
        _barracksHeroes(state) {
            return state.barracksHeroes;
        }
    },
    mutations: {
        SET_BARRACKS_HEROES(state, payload) {
            state.barracksHeroes = payload;
        },
    },

    actions: {
        async updateBarracks({commit, dispatch}, route) {
            let heroesResponse = await squadApi.getBarracksHeroes(route.params.squadSlug);
            let heroes = heroesResponse.map(function (hero) {
                return new BarracksHero(hero);
            });
            commit('SET_BARRACKS_HEROES', heroes);
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

        async emptyHeroSlot({state, commit, dispatch}, {hero, slot}) {

            let transactionResponse = await heroApi.emptySlot(hero.slug, slot.uuid);
            console.log("Transaction");
            console.log(transactionResponse);
        }
    }
};
