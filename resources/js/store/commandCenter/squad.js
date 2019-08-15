import * as squadApi from '../../api/squadApi';

export default {

    state: {
        squad: {}
    },

    getters: {
        _squad(state) {
            return state.squad;
        },
        _availableSpiritEssence(state) {
            return state.squad.availableSpiritEssence;
        }
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.squad = payload;
        },
        UPDATE_HERO(state, payload) {
            // state.squad = new Squad();
            let current = state.squad;
            current.heroPosts.forEach(function (heroPost) {
                if (heroPost.hero && heroPost.hero.uuid === payload.uuid) {
                    heroPost.hero = payload;
                }
            });
            state.squad = current;
        },

    },

    actions: {
        async updateSquad({commit}, route) {
            let squad = await squadApi.getSquad(route.params.squadSlug);
            commit('SET_SQUAD', squad)
        },
        updateHero({commit}, payload) {
            commit('UPDATE_HERO', payload)
        },
    }
};
