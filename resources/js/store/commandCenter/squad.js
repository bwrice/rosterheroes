
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
        setSquad({commit}, payload) {
            commit('SET_SQUAD', payload)
        },
        updateHero({commit}, payload) {
            commit('UPDATE_HERO', payload)
        },
    }
};