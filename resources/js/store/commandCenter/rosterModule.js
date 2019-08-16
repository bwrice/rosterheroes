import * as weekApi from '../../api/weekApi';
import * as squadApi from '../../api/squadApi';
import * as heroApi from '../../api/heroApi';

export default {

    state: {
        playerSpiritsPool: [],
        rosterHeroes: [],
        rosterFocusedHero: {
            name: '',
            playerSpirit: null,
            heroRace: {
                positions: []
            }
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

            if ('roster-hero' === route.name) {
                dispatch('setRosterFocusedHeroBySlug', route.params.heroSlug);
            }

            let playerSpirits = await weekApi.getCurrentPlayerSpirits();
            commit('SET_PLAYER_SPIRITS_POOL', playerSpirits);
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
        },

        async addSpiritToHero({state, commit}, payload) {
            let updatedHero = await heroApi.addSpirit(payload.heroSlug, payload.spiritUuid);
            let rosterHeroes = _.cloneDeep(state.rosterHeroes);
            let index = rosterHeroes.findIndex(function (hero) {
                return hero.uuid === updatedHero.uuid;
            });

            if (index) {
                rosterHeroes.splice(index, 1, updatedHero);
                commit('SET_ROSTER_HEROES', rosterHeroes);
            }
        }
    }
};
