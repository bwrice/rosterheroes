<template>
    <v-col cols="12" md="8" offset-md="2">
        <v-card>
            <v-container class="pa-0">
                <v-row no-gutters>
                    <v-col cols="5" class="pa-1">
                        <v-row no-gutters>
                            <v-col cols="12">
                                <TravelRouteMap></TravelRouteMap>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <h5 class="text-center">Current Route</h5>
                                <div style="min-height:150px; max-height:150px; overflow-y:auto">
                                    <v-card class="pa-1" flat>
                                        <TravelRouteListItem
                                            v-for="(province, uuid) in routeList"
                                            :province="province"
                                            :key="uuid"
                                            :color="routeItemColor(province)"
                                        >
                                        </TravelRouteListItem>
                                        <v-sheet tile :color="'#a969b3'" class="pa-1">
                                            {{this._currentLocation.name}}
                                        </v-sheet>
                                    </v-card>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-sheet class="pa-1">
                                    <v-btn
                                        :disabled="emptyRoute"
                                        color="warning"
                                        block
                                        @click="removeLastRoutePosition"
                                    >
                                        Undo
                                    </v-btn>
                                </v-sheet>
                            </v-col>
                            <v-col cols="12">
                                <v-sheet class="pa-1">
                                    <v-btn
                                        :disabled="emptyRoute"
                                        color="error"
                                        block
                                        @click="clearTravelRoute"
                                    >
                                        Clear Route
                                    </v-btn>
                                </v-sheet>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="7" class="pa-1">
                        <v-row no-gutters>
                            <v-col class="xs12">
                                <MapViewPort :tile="true" :view-box="currentViewBox">

                                    <!-- Borders -->
                                    <ProvinceVector
                                        v-for="(province, uuid) in borders"
                                        :key="uuid"
                                        :province="province"
                                        @provinceClicked="addToRoute"
                                        :fill-color="borderColor(province)"
                                        :highlight="provinceInRoute(province)"
                                    >
                                    </ProvinceVector>

                                    <ProvinceVector
                                        :province="_routePosition"
                                        :highlight="true"
                                        @provinceClicked="snackBarError({text: 'Click on a border to add to your route'})"
                                    >
                                    </ProvinceVector>
                                </MapViewPort>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-card flat class="pa-3">
                                    <MapControls
                                        @panUp="panUp"
                                        @panDown="panDown"
                                        @panLeft="panLeft"
                                        @panRight="panRight"
                                        @zoomIn="zoomIn"
                                        @zoomOut="zoomOut"
                                        @reset="restViewBox"
                                    ></MapControls>
                                </v-card>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-sheet class="pa-2">
                                    <v-btn
                                        :disabled="emptyRoute"
                                        color="success"
                                        x-large
                                        block
                                        @click="travelDialog = true"
                                    >
                                        Travel
                                    </v-btn>
                                </v-sheet>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
        <v-dialog
            v-model="travelDialog"
            max-width="920"
        >
            <v-card>
                <v-card-title class="headline">Travel to {{_routePosition.name}}?</v-card-title>
                <TravelRouteMap></TravelRouteMap>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        text
                        @click="travelDialog = false"
                    >
                        Cancel
                    </v-btn>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="confirmTravel({
                            route: $route,
                            router: $router
                        })"
                    >
                        Confirm Travel
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-col>
</template>

<script>

    import {mapActions} from 'vuex';
    import {mapGetters} from 'vuex';

    import {viewBoxControlsMixin} from "../../../../mixins/viewBoxControlsMixin";
    import {travelMixin} from "../../../../mixins/travelMixin";
    import {bordersMixin} from "../../../../mixins/bordersMixin";

    import MapViewPort from "../../map/MapViewPort";
    import ProvinceVector from "../../map/ProvinceVector";
    import TravelRouteListItem from "../../map/TravelRouteListItem";
    import MapControls from "../../map/MapControls";
    import TravelRouteMap from "../../map/TravelRouteMap";

    export default {
        name: "TravelView",
        components: {
            TravelRouteMap,
            MapControls,
            TravelRouteListItem,
            ProvinceVector,
            MapViewPort
        },
        mixins: [
            viewBoxControlsMixin,
            travelMixin,
            bordersMixin
        ],
        mounted() {
            this.setViewBox(this._routePosition.view_box);
        },
        data: function() {
            return {
                travelDialog: false
            }
        },
        watch: {
            _routePosition: function(newValue) {
                this.setViewBox(newValue.view_box);
            }
        },
        methods: {
            ...mapActions([
                'setSquad',
                'setCurrentLocation',
                'extendTravelRoute',
                'clearTravelRoute',
                'removeLastRoutePosition',
                'snackBarError',
                'snackBarSuccess',
                'confirmTravel'
            ]),
            routeItemColor(province) {
                if (province.uuid === this._routePosition.uuid) {
                    return 'success';
                }
                return 'primary';
            },
            borderColor(province) {
                if (this.provinceInRoute(province)) {
                    return '#4a4a4a';
                }
                return province.color;
            },
            addToRoute(province) {
                if (province.uuid === this._currentLocation.uuid) {
                    this.snackBarError({
                        text: "You're already in " + province.name
                    });
                }else if(this.provinceInRoute(province)) {
                    this.snackBarError({
                        text: province.name + ' is already part of your route'
                    });
                } else {
                    this.extendTravelRoute(province);
                }
            },
        },

        computed: {
            ...mapGetters([
                '_squad',
            ]),
            oceanColor() {
                return '#000000';
            },
            routeList() {
                let travelRoute = _.cloneDeep(this._travelRoute);
                return travelRoute.reverse();
            },
            emptyRoute() {
                return ! this._travelRoute.length;
            },
            // needed for borders mixin
            province() {
                return this._routePosition;
            }
        }
    }
</script>

<style scoped>

</style>
