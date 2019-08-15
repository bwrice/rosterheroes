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
        async updateBarracksHeroes({commit, dispatch}, route) {
            let heroes = await squadApi.getBarracksHeroes(route.params.squadSlug);
            commit('SET_BARRACKS_HEROES', heroes);
            if (route.params.heroSlug) {
                dispatch('setBarracksFocusedHeroBySlug', route.params.heroSlug);
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
