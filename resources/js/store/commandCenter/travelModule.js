
export default {

    state: {
        start: null,
        route: []
    },

    getters: {
        _travelStart(state) {
            return state.start;
        },
        _travelRoute(state) {
            return state.route;
        }
    },
    mutations: {
        SET_TRAVEL_START(state, payload) {
            state.start = payload;
        },
        ADD_TO_TRAVEL_ROUTE(state, payload) {
            state.route.push(payload);
        }
    },

    actions: {
        setTravelStart({commit}, payload) {
            commit('SET_TRAVEL_START', payload)
        },
        addToTravelRoute({commit}, payload) {
            commit('ADD_TO_TRAVEL_ROUTE', payload)
        }
    }
};
