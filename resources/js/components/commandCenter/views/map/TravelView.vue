<template>
    <v-flex class="xs12">
        <v-card>
            <v-layout>
                <v-flex class="xs5">
                    <v-layout>
                        <v-flex class="xs12">
                            <!-- Mini-map -->
                            <MapViewPort :ocean-color="oceanColor">
                                <ProvinceVector
                                    v-for="(province, uuid) in this._provinces"
                                    :key="uuid"
                                    :province="province"
                                    :fill-color="minimMapProvinceColor(province)"
                                >
                                </ProvinceVector>
                            </MapViewPort>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex class="xs12">
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
                        </v-flex>
                    </v-layout>
                    <v-layout row class="px-3">
                        <v-flex class="xs12">
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
                        </v-flex>
                        <v-flex class="xs12">
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
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex class="xs7">
                    <v-layout>
                        <v-flex class="xs12">
                            <MapViewPort :tile="true" :view-box="currentViewBox">

                                <!-- Borders -->
                                <ProvinceVector
                                    v-for="(province, uuid) in _routePosition.borders"
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
                                    @provinceClicked="snackBarError('Click on a border to add to your route')"
                                >
                                </ProvinceVector>
                            </MapViewPort>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex class="xs12">
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
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex class="xs12">
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
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
        </v-card>
        <v-dialog
            v-model="travelDialog"
            max-width="920"
        >
            <v-card>
                <v-card-title class="headline">Travel to {{_routePosition.name}}?</v-card-title>

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
                        @click="travelDialog = false"
                    >
                        Confirm Travel
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-flex>
</template>

<script>

    import {mapActions} from 'vuex'
    import {mapGetters} from 'vuex';
    import {viewBoxControlsMixin} from "../../../../mixins/viewBoxControlsMixin";

    import MapViewPort from "../../map/MapViewPort";
    import ProvinceVector from "../../map/ProvinceVector";
    import TravelRouteListItem from "../../map/TravelRouteListItem";
    import MapControls from "../../map/MapControls";

    export default {
        name: "TravelView",
        components: {
            MapControls,
            TravelRouteListItem,
            ProvinceVector,
            MapViewPort
        },
        mixins: [
            viewBoxControlsMixin
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
                'setCurrentLocation',
                'extendTravelRoute',
                'clearTravelRoute',
                'removeLastRoutePosition',
                'snackBarError'
            ]),
            minimMapProvinceColor(province) {
                if (province.uuid === this._currentLocation.uuid) {
                    return '#dd00ff';
                } else if (province.uuid === this._routePosition.uuid) {
                    return '#4ef542';
                } else if (this.provinceInRoute(province)) {
                    return '#035afc'
                }
                return '#dedede';
            },
            routeItemColor(province) {
                if (province.uuid === this._routePosition.uuid) {
                    return 'success';
                }
                return 'primary';
            },
            provinceInRoute(province) {
                let value = false;
                this._travelRoute.forEach(function (routeProvince) {
                    if (routeProvince.uuid === province.uuid) {
                        value = true;
                    }
                });
                return value;
            },
            borderColor(province) {
                if (this.provinceInRoute(province)) {
                    return '#4a4a4a';
                }
                return province.color;
            },
            addToRoute(province) {
                if (province.uuid === this._currentLocation.uuid) {
                    this.snackBarError("You're already in " + province.name);
                }else if(this.provinceInRoute(province)) {
                    this.snackBarError(province.name + ' is already part of your route');
                } else {
                    this.extendTravelRoute(province);
                }
            }
        },

        computed: {
            ...mapGetters([
                '_currentLocation',
                '_routePosition',
                '_provinces',
                '_travelRoute'
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
            }
        }
    }
</script>

<style scoped>

</style>
