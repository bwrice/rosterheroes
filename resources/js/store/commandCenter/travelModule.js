import * as squadApi from '../../api/squadApi';
import Province from "../../models/Province";

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
                return getters._currentLocationProvince;
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
        async confirmTravel({state, commit, dispatch}, {route, router}) {
            dispatch('setOverlay', {show: true});

            let travelRoute = state.travelRoute.map(function (province) {
                return province.uuid;
            });

            let squadSlug = route.params.squadSlug;

            try {

                let fastTravelResponse = await squadApi.fastTravel(squadSlug, travelRoute);
                let currentLocationProvince = new Province(fastTravelResponse.data);
                dispatch('updateCurrentLocation', route, {
                    province: currentLocationProvince
                });
                commit('CLEAR_TRAVEL_ROUTE');
                dispatch('updateSquad', route);
                dispatch('snackBarSuccess', {
                    'text': 'Welcome to ' + currentLocationProvince.name,
                    timeout: 4000

                });
                router.push({
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
