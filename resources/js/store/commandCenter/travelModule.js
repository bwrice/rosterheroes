import Province from "../../models/Province";

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
        SET_ROUTE_BORDERS(state, payload) {
            state.routePositionBorders = payload;
        },
        ADD_TO_TRAVEL_ROUTE(state, payload) {
            state.route.push(payload);
        }
    },

    actions: {
        async extendTravelRoute({commit}, payload) {
            commit('ADD_TO_TRAVEL_ROUTE', payload);
            commit('SET_ROUTE_POSITION', payload);
            let province = new Province(payload);
            let borders = await Province.custom(province, 'borders').$get();
            commit('SET_ROUTE_BORDERS', borders);
        }
    }
};
