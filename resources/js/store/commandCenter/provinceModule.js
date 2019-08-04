import Province from "../../models/Province";

export default {
    state: {
        province: {
            view_box: {
                'pan_x': 0,
                'pan_y': 0,
                'zoom_x': 315,
                'zoom_y': 240,
            }
        },
        borders: []
    },

    getters: {
        _province(state) {
            return state.province;
        },
        _borders(state) {
            return state.borders;
        },
    },
    mutations: {
        SET_PROVINCE(state, payload) {
            state.province = payload;
        },
        SET_BORDERS(state, payload) {
            state.borders = payload;
        },
    },

    actions: {
        async setProvince({commit}, payload) {
            commit('SET_PROVINCE', payload);
            let province = new Province(payload);
            let borders = await Province.custom(province, 'borders').$get();
            commit('SET_BORDERS', borders);
        },
    }
};
