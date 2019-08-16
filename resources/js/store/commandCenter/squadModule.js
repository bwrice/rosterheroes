import * as squadApi from '../../api/squadApi';

export default {

    state: {
        squad: {}
    },

    getters: {
        _squad(state) {
            return state.squad;
        }
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.squad = payload;
        }
    },

    actions: {
        async updateSquad({commit}, route) {
            let squad = await squadApi.getSquad(route.params.squadSlug);
            commit('SET_SQUAD', squad)
        }
    }
};
