import * as squadApi from '../../api/squadApi';
import Province from "../../models/Province";
import TravelRouteDestination from "../../models/TravelRouteDestination";
import * as helpers from "../../helpers/vuexHelpers";

export default {

    state: {
        travelRoute: []
    },

    getters: {
        _finalDestination(state, getters) {
            let length = state.travelRoute.length;
            if (length) {
                // Return last element of route
                return state.travelRoute[length - 1];
            } else {
                return null;
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
        async extendTravelRoute({commit, rootState}, border) {
            try {
                let squad = rootState.squadModule.squad;
                let response = await squadApi.getBorderTravelCost(squad.slug, border.slug);
                commit('ADD_TO_TRAVEL_ROUTE', new TravelRouteDestination({
                    cost: response.data.cost,
                    province: border
                }));
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

            let travelRoute = state.travelRoute.map(function (travelDestination) {
                return travelDestination.province.uuid;
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
                helpers.handleResponseErrors(e, 'travel', dispatch);
            }
        }

    }
};
