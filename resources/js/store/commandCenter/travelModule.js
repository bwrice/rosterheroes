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
        async extendTravelRoute({commit, rootState}, payload) {
            try {
                let squad = rootState.squadModule.squad;
                let response = await axios.get('/api/v1/squads/' + squad.slug + '/border/' + payload.slug);
                let routeProvince = response.data;
                commit('SET_ROUTE_POSITION', routeProvince);
                commit('ADD_TO_TRAVEL_ROUTE', routeProvince);
                commit('SET_ROUTE_BORDERS', []);
                let bordersResponse = await axios.get('/api/v1/provinces/' + routeProvince.slug + '/borders');
                let borders = bordersResponse.data.data;
                commit('SET_ROUTE_BORDERS', borders);
            } catch (error) {
                console.log("ERROR");
                console.log(error);
            }
        }
    }
};
