
export default {

    state: {
        routePosition: null,
        route: []
    },

    getters: {
        _routePosition(state) {
            return state.routePosition;
        },
        _travelRoute(state) {
            return state.route;
        }
    },
    mutations: {
        SET_ROUTE_POSITION(state, payload) {
            state.start = payload;
        },
        ADD_TO_TRAVEL_ROUTE(state, payload) {
            state.route.push(payload);
        }
    },

    actions: {
        setRoutePosition({commit}, payload) {
            commit('SET_ROUTE_POSITION', payload)
        },
        addToTravelRoute({commit}, payload) {
            commit('ADD_TO_TRAVEL_ROUTE', payload)
        }
    }
};
