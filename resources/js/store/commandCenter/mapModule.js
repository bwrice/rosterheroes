import Province from "../../models/Province";

export default {

    state: {
        provinces: []
    },

    getters: {
        _provinces(state) {
            return state.provinces;
        }
    },
    mutations: {
        UPDATE_PROVINCES(state, payload) {
            state.provinces = payload;
        }
    },

    actions: {
        async updateProvinces({commit}) {
            let provinces = Province.$get();
            commit('UPDATE_PROVINCES', provinces)
        },
    }
};