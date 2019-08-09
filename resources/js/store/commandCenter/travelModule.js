import Province from "../../models/Province";

export default {

    state: {
        routePosition: null,
        travelRoute: []
    },

    getters: {
        _routePosition(state, getters, rootState) {
            if (state.routePosition) {
                return state.routePosition;
            } else {
                return rootState.currentLocationModule.currentLocation;
            }
        },
        _travelRoute(state) {
            return state.travelRoute;
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
            state.travelRoute.push(payload);
        },
        CLEAR_TRAVEL_ROUTE(state, payload) {
            state.travelRoute = [];
        }
    },

    actions: {
        async extendTravelRoute({commit, rootState}, payload) {
            try {
                let squad = rootState.squadModule.squad;
                let response = await axios.get('/api/v1/squads/' + squad.slug + '/border/' + payload.slug);
                let routeProvince = response.data;
                commit('SET_ROUTE_POSITION', routeProvince);
                commit('ADD_TO_TRAVEL_ROUTE', routeProvince);
                commit('SET_ROUTE_BORDERS', borders);
            } catch (error) {
                console.log("ERROR");
                console.log(error);
            }
        },
        clearTravelRoute({commit, rootState}) {
            commit('CLEAR_TRAVEL_ROUTE');
        }
    }
};
