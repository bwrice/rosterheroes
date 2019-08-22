import * as squadApi from '../../api/squadApi';
import * as measurableApi from '../../api/measurableApi';

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
            let heroes = await squadApi.getBarracksHeroes(route.params.squadSlug);
            commit('SET_BARRACKS_HEROES', heroes);
        },
        async raiseHeroMeasurable({state, commit}, payload) {

            try {
                let updatedMeasurable = await measurableApi.raise(payload.measurableUuid, payload.raiseAmount);
                let updatedHeroes = _.cloneDeep(state.barracksHeroes);
                let matchingHero = updatedHeroes.find(function (hero) {
                    return payload.heroSlug === hero.slug;
                });

                let measurableIndex = matchingHero.measurables.findIndex(function (measurable) {
                    return measurable.uuid === updatedMeasurable.uuid;
                });

                matchingHero.measurables.splice(measurableIndex, 1, updatedMeasurable);

                console.log("Matching Hero");
                console.log(matchingHero);
                console.log("UPDATED HEROES");
                console.log(updatedHeroes);
                commit('SET_BARRACKS_HEROES', updatedHeroes);

            } catch(e) {
                console.log("ERRORS");
                console.log(e);
            }
        }
    }
};
