import * as squadApi from '../../api/squadApi';

export default {

    state: {
        barracksHeroes: [],
        barracksFocusedHero: {
            name: '',
            slug: '',
            measurables: []
        }
    },

    getters: {
        _barracksHeroes(state) {
            return state.barracksHeroes;
        },
        _barracksFocusedHero(state) {
            return state.barracksFocusedHero;
        }
    },
    mutations: {
        SET_BARRACKS_HEROES(state, payload) {
            state.barracksHeroes = payload;
        },
        SET_BARRACKS_FOCUSED_HERO(state, payload) {
            state.barracksFocusedHero = payload;
        }
    },

    actions: {
        async updateBarracksHeroes({commit, dispatch}, routeParams) {
            let heroes = await squadApi.getBarracksHeroes(routeParams.squadSlug);
            commit('SET_BARRACKS_HEROES', heroes);
            if (routeParams.heroSlug) {
                dispatch('setBarracksFocusedHeroBySlug', routeParams.heroSlug);
            }
        },
        setBarracksFocusedHeroBySlug({state, commit}, heroSlug) {
            if (heroSlug !== state.barracksFocusedHero.slug) {
                let focusedHero = state.barracksHeroes.find(function (hero) {
                    return hero.slug === heroSlug;
                });

                if (focusedHero) {
                    commit('SET_BARRACKS_FOCUSED_HERO', focusedHero);
                }
            }
        }
    }
};
