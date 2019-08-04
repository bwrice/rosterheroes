export default {

    state: {
        province: null
    },

    getters: {
        _currentProvince(state) {
            return state.province;
        }
    },
    mutations: {
        SET_PROVINCE(state, payload) {
            state.province = payload;
        }
    },

    actions: {
        setProvince({commit}, payload) {
            commit('SET_PROVINCE', payload)
        },
    }
};
