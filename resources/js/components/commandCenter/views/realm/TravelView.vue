<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <MapViewPort :view-box="focusedProvince.viewBox">
                            <!-- Border Borders -->
                            <ProvinceVector
                                v-for="(province, uuid) in borderBorders"
                                :key="province.uuid"
                                :province="province"
                                :fill-color="'#808080'"
                                :highlight="false"
                                :hoverable="false"
                            >
                            </ProvinceVector>
                            <!-- Borders -->
                            <ProvinceVector
                                v-for="(province, uuid) in borders"
                                :key="province.uuid"
                                :province="province"
                                @provinceClicked="addToRoute"
                                :fill-color="borderColor(province)"
                                :highlight="provinceInRoute(province)"
                                :hoverable="true"
                            >
                            </ProvinceVector>

                            <ProvinceVector
                                :province="focusedProvince"
                                :highlight="true"
                                @provinceClicked="snackBarError({text: 'Click on a border to add to your route'})"
                            >
                            </ProvinceVector>
                        </MapViewPort>
                    </v-col>
                </v-row>
                <v-row no-gutters class="py-2">
                    <v-col cols="6">
                        <MapViewPort :ocean-color="'#000'" @click="travelDialog = true" class="rh-clickable">
                            <ProvinceVector
                                v-for="(province, uuid) in this._provinces"
                                :key="uuid"
                                :province="province"
                                :fill-color="minimMapProvinceColor(province)"
                            >
                            </ProvinceVector>
                            <MapWindow :view-box="focusedProvince.viewBox"></MapWindow>
                        </MapViewPort>
                    </v-col>
                    <v-col cols="6">
                        <v-row no-gutters class="px-1 pb-1">
                            <v-btn
                                :disabled="! _destinationUuid"
                                color="warning"
                                block
                                outlined
                                @click="clearTravelDestination"
                            >
                                Clear Dest.
                            </v-btn>
                        </v-row>
                        <v-row no-gutters class="px-1 pb-1">
                            <v-btn
                                :disabled="emptyRoute"
                                color="warning"
                                block
                                outlined
                                @click="removeLastRoutePosition"
                            >
                                Undo
                            </v-btn>
                        </v-row>
                        <v-row no-gutters class="px-1 pb-1">
                            <v-btn
                                :disabled="emptyRoute"
                                color="error"
                                block
                                outlined
                                @click="clearTravelRoute"
                            >
                                Clear Route
                            </v-btn>
                        </v-row>
                        <v-row no-gutters class="px-1">
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
            <v-col cols="12" md="6" lg="5" xl="4">
                <v-row no-gutters>
                    <span class="title font-weight-thin">TRAVEL ROUTE</span>
                </v-row>
                <v-sheet style="background-color: hsla(0,0%,100%,.12); color: hsla(0,0%,100%,.6)">
                <v-row v-if="! routeList.length" no-gutters justify="center">
                    <span class="title font-weight-thin">(empty)</span>
                </v-row>
                </v-sheet>
                <TravelRouteListItem
                    v-for="(destination, id) in routeList"
                    :key="id"
                    :travel-destination="destination"
                    :color="routeItemColor(destination)"
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
                        <v-row no-gutters justify="center" align="end" class="py-2">
                            <span class="subtitle-1 font-weight-thin">
                                DESTINATION:
                            </span>
                            &nbsp;
                            <span class="title font-weight-bold">
                                {{focusedProvince.name}}
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
                        <v-row no-gutters justify="end" align="center" class="pa-2">
                            <v-btn
                                outlined
                                color="error"
                                @click="travelDialog = false"
                                class="mx-1"
                            >
                                Close
                            </v-btn>
                            <v-btn
                                color="success"
                                :disabled="_travelRoute.length <= 0"
                                class="mx-1"
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
    import ViewBox from "../../../../models/ViewBox";
    import MapViewPort from "../../realm/MapViewPort";
    import MapWindow from "../../realm/MapWindow";

    export default {
        name: "TravelView",
        components: {
            MapWindow,
            MapViewPort,
            MapControls,
            TravelRouteListItem,
            ProvinceVector
        },
        data: function() {
            return {
                travelDialog: false,
                positionViewBox: new ViewBox({}),
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
                'clearTravelDestination',
            ]),
            routeItemColor(travelRouteDestination) {
                if (travelRouteDestination.province.uuid === this.focusedProvince.uuid) {
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
                    return '#4ef542';
                } else if (province.uuid === this.focusedProvince.uuid) {
                    if (province.uuid === this._destinationUuid) {
                        return '#da19fc';
                    }
                    return '#1ac4b3';
                } else if (province.uuid === this._destinationUuid) {
                    return '#035afc'
                } else if (this.provinceInRoute(province)) {
                    return '#ed7a28'
                }
                return '#dedede';
            },
            provinceInRoute(province) {
                let value = false;
                this._travelRoute.forEach(function (travelDestination) {
                    if (travelDestination.province.uuid === province.uuid) {
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
                '_finalDestination',
                '_provinces',
                '_travelRoute',
                '_destinationUuid'
            ]),
            routeList() {
                let travelRoute = _.cloneDeep(this._travelRoute);
                return travelRoute.reverse();
            },
            emptyRoute() {
                return ! this._travelRoute.length;
            },
            borders() {
                return this._provincesByUuids(this.focusedProvince.borderUuids);
            },
            borderBorders() {
                let borderBorders = [];
                let uuidsToIgnore = this.borders.map(border => border.uuid);
                uuidsToIgnore.push(this.focusedProvince.uuid);
                let self = this;
                this.borders.forEach(function (border) {
                    let alreadyIncludedUuids = borderBorders.map(border => border.uuid);
                    let filteredUuids = border.borderUuids.filter(function (uuid) {
                        return ! uuidsToIgnore.includes(uuid) && ! alreadyIncludedUuids.includes(uuid);
                    })
                    borderBorders.push(...self._provincesByUuids(filteredUuids));
                })
                return borderBorders;
            },
            focusedProvince() {
                if (this._finalDestination) {
                    return this._finalDestination.province;
                }
                return this._currentLocationProvince;
            }
        }
    }
</script>

<style scoped>

</style>
