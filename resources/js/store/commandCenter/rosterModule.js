import * as weekApi from '../../api/weekApi';
import * as squadApi from '../../api/squadApi';

export default {

    state: {
        playerSpiritsPool: [],
        rosterHeroes: [],
        rosterFocusedHero: {
            name: '',
            playerSpirit: null
        }
    },

    getters: {
        _playerSpiritsPool(state) {
            return state.playerSpiritsPool;
        },
        _rosterHeroes(state) {
            return state.rosterHeroes;
        },
        _rosterFocusedHero(state) {
            return state.rosterFocusedHero;
        }
    },
    mutations: {
        SET_PLAYER_SPIRITS_POOL(state, payload) {
            state.playerSpiritsPool = payload;
        },
        SET_ROSTER_HEROES(state, payload) {
            state.rosterHeroes = payload;
        },
        SET_ROSTER_FOCUSED_HERO(state, payload) {
            state.rosterFocusedHero = payload;
        },
    },

    actions: {
        async updateRoster({commit, dispatch}, route) {

            let heroes = await squadApi.getRosterHeroes(route.params.squadSlug);
            commit('SET_ROSTER_HEROES', heroes);

            let playerSpirits = await weekApi.getCurrentPlayerSpirits();
            commit('SET_PLAYER_SPIRITS_POOL', playerSpirits);

            if ('roster-hero' === route.name) {
                dispatch('setRosterFocusedHeroBySlug', route.params.heroSlug);
            }
        },

        setRosterFocusedHeroBySlug({state, commit}, heroSlug) {
            let hero = state.rosterHeroes.find(function (hero) {
                return hero.slug === heroSlug;
            });
            if (hero) {
                commit('SET_ROSTER_FOCUSED_HERO', hero);
            } else {
                console.warn("Couldn't set roster focused hero by slug: " + heroSlug);
            }
        }
    }
};
