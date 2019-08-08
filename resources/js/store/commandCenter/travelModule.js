
export default {

    state: {
        routePosition: {
            name: ''
        },
        routePositionBorders: [],
        route: []
    },

    getters: {
        _routePosition(state) {
            return state.routePosition;
        },
        _routePositionBorders(state) {
            return state.routePositionBorders;
        },
        _travelRoute(state) {
            return state.route;
        }
    },
    mutations: {
        SET_CURRENT_LOCATION(state, payload) {
            state.routePosition = payload;
            state.routePositionBorders = payload.borders;
        },
        SET_ROUTE_POSITION(state, payload) {
            state.routePosition = payload;
        },
        ADD_TO_TRAVEL_ROUTE(state, payload) {
            state.route.push(payload);
        }
    },

    actions: {
        addToTravelRoute({commit}, payload) {
            commit('ADD_TO_TRAVEL_ROUTE', payload);
            commit('SET_ROUTE_POSITION', payload);
        }
    }
};
