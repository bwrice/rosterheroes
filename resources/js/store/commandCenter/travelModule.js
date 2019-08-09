import Province from "../../models/Province";

export default {

    state: {
        travelRoute: []
    },

    getters: {
        _routePosition(state, getters, rootState) {
            let length = state.travelRoute.length;
            if (length) {
                // Return last element of route
                return state.travelRoute[length - 1];
            } else {
                return rootState.currentLocationModule.currentLocation;
            }
        },
        _travelRoute(state) {
            return state.travelRoute;
        },
    },
    mutations: {
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
                commit('ADD_TO_TRAVEL_ROUTE', routeProvince);
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
