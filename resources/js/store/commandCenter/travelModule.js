import * as squadApi from '../../api/squadApi';
import CurrentLocation from "../../models/CurrentLocation";

export default {

    state: {
        travelRoute: []
    },

    getters: {
        _routePosition(state, getters) {
            let length = state.travelRoute.length;
            if (length) {
                // Return last element of route
                return state.travelRoute[length - 1];
            } else {
                // route empty, return current location
                return getters._currentLocation;
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
        CLEAR_TRAVEL_ROUTE(state) {
            state.travelRoute = [];
        },
        REMOVE_LAST_ROUTE_POSITION(state) {
            state.travelRoute.pop();
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
                //
            }
        },
        clearTravelRoute({commit}) {
            commit('CLEAR_TRAVEL_ROUTE');
        },
        removeLastRoutePosition({commit}) {
            commit('REMOVE_LAST_ROUTE_POSITION');
        },
        async confirmTravel({state, commit, dispatch}, payload) {
            dispatch('setOverlay', {show: true});

            let travelRoute = state.travelRoute.map(function (province) {
                return province.uuid;
            });

            let squadSlug = payload.route.params.squadSlug;

            try {

                let fastTravelResponse = await squadApi.fastTravel(squadSlug, travelRoute);
                let currentLocation = new CurrentLocation(fastTravelResponse.data);
                commit('SET_CURRENT_LOCATION', currentLocation);
                commit('CLEAR_TRAVEL_ROUTE');
                dispatch('updateSquad', payload.route);
                dispatch('snackBarSuccess', {
                    'text': 'Welcome to ' + currentLocation.name,
                    timeout: 4000

                });
                payload.router.push({
                    name: 'map-main',
                    params: {
                        squadSlug: squadSlug
                    }});

            } catch (e) {
                let errors = e.response.data.errors;
                let snackBarPayload = {};
                if (errors && errors.travel) {
                    snackBarPayload = {
                        text: errors.travel[0]
                    }
                }
                dispatch('snackBarError', snackBarPayload)
            }

            dispatch('stopOverlay');
        }

    }
};
