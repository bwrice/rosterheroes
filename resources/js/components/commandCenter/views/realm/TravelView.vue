<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="6" lg="4" offset-lg="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <MapViewPort :view-box="positionViewBox">
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
                <v-row no-gutters class="py-2">
                    <v-col cols="6">
                        <MapViewPort :ocean-color="'#000'" @click="travelDialog = true">
                            <ProvinceVector
                                v-for="(province, uuid) in this._provinces"
                                :key="uuid"
                                :province="province"
                                :fill-color="minimMapProvinceColor(province)"
                            >
                            </ProvinceVector>
                            <MapWindow :view-box="positionViewBox"></MapWindow>
                        </MapViewPort>
                    </v-col>
                    <v-col cols="6">
                        <v-row no-gutters class="pa-1">
                            <v-btn
                                :disabled="emptyRoute"
                                color="warning"
                                block
                                @click="removeLastRoutePosition"
                            >
                                Undo
                            </v-btn>
                        </v-row>
                        <v-row no-gutters class="pa-1">
                            <v-btn
                                :disabled="emptyRoute"
                                color="error"
                                block
                                @click="clearTravelRoute"
                            >
                                Clear Route
                            </v-btn>
                        </v-row>
                        <v-row no-gutters class="pa-1">
                            <v-btn
                                :disabled="emptyRoute"
                                color="success"
                                block
                                @click="travelDialog = true"
                            >
                                Travel
                            </v-btn>
                        </v-row>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12" md="6" lg="4">
                <v-row no-gutters>
                    <span class="title font-weight-thin">TRAVEL ROUTE</span>
                </v-row>
                <v-sheet style="background-color: hsla(0,0%,100%,.12); color: hsla(0,0%,100%,.6)">
                <v-row v-if="! routeList.length" no-gutters justify="center">
                    <span class="title font-weight-thin">(empty)</span>
                </v-row>
                </v-sheet>
                <TravelRouteListItem
                    v-for="(province, uuid) in routeList"
                    :province="province"
                    :key="uuid"
                    :color="routeItemColor(province)"
                >
                </TravelRouteListItem>
            </v-col>
        </v-row>
        <v-dialog
            v-model="travelDialog"
            max-width="600"
        >
            <v-sheet>
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row no-gutters justify="center" class="py-2">
                            <span class="title font-weight-thin">
                                Travel to {{_routePosition.name}}?
                            </span>
                        </v-row>
                        <v-row no-gutters class="pa-1">
                            <v-col cols="12">
                                <MapViewPort :ocean-color="'#000'">
                                    <ProvinceVector
                                        v-for="(province, uuid) in this._provinces"
                                        :key="uuid"
                                        :province="province"
                                        :fill-color="minimMapProvinceColor(province)"
                                    >
                                    </ProvinceVector>
                                </MapViewPort>
                            </v-col>
                        </v-row>
                        <v-row no-gutters justify="space-around" align="center" class="py-1">
                            <v-btn
                                outlined
                                color="error"
                                @click="travelDialog = false"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="success"
                                :disabled="_travelRoute.length <= 0"
                                @click="confirmTravel({route: $route,router: $router})"
                            >
                                Confirm Travel
                            </v-btn>
                        </v-row>
                    </v-col>
                </v-row>
            </v-sheet>
        </v-dialog>
    </v-container>
</template>

<script>

    import {mapActions} from 'vuex';
    import {mapGetters} from 'vuex';

    import ProvinceVector from "../../realm/ProvinceVector";
    import TravelRouteListItem from "../../realm/TravelRouteListItem";
    import MapControls from "../../realm/MapControls";
    import TravelRouteMap from "../../realm/TravelRouteMap";
    import ViewBox from "../../../../models/ViewBox";
    import MapViewPort from "../../realm/MapViewPort";
    import MapWindow from "../../realm/MapWindow";

    export default {
        name: "TravelView",
        components: {
            MapWindow,
            MapViewPort,
            TravelRouteMap,
            MapControls,
            TravelRouteListItem,
            ProvinceVector
        },
        mounted() {
            this.setPositionViewBox(this._routePosition.viewBox);
        },
        data: function() {
            return {
                travelDialog: false,
                positionViewBox: new ViewBox({}),
            }
        },
        watch: {
            _routePosition: function(newValue) {
                this.setPositionViewBox(newValue.viewBox);
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
                'confirmTravel',
            ]),
            setPositionViewBox(viewBox) {
                this.positionViewBox = viewBox;
            },
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
                if (province.uuid === this._currentLocationProvince.uuid) {
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
            minimMapProvinceColor(province) {
                if (province.uuid === this._currentLocationProvince.uuid) {
                    return '#dd00ff';
                } else if (province.uuid === this._routePosition.uuid) {
                    return '#4ef542';
                } else if (this.provinceInRoute(province)) {
                    return '#035afc'
                }
                return '#dedede';
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
        },

        computed: {
            ...mapGetters([
                '_squad',
                '_provincesByUuids',
                '_currentLocationProvince',
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
            },
            // needed for borders mixin
            province() {
                return this._routePosition;
            },
            borders() {
                return this._provincesByUuids(this._routePosition.borderUuids);
            }
        }
    }
</script>

<style scoped>

</style>
