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
                            <div style=" max-height:200px; overflow-y:auto">
                                <v-card class="pa-1" :max-height="300">
                                    <TravelRouteListItem
                                        v-for="(province, uuid) in routeList"
                                        :province="province"
                                        :key="uuid"
                                        :color="routeItemColor(province)"
                                    >
                                    </TravelRouteListItem>
                                    <v-sheet tile :color="routeItemColor(_currentLocation)" class="pa-1">
                                        {{this._currentLocation.name}}
                                    </v-sheet>
                                </v-card>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex class="xs7">
                    <MapViewPort :tile="true" :view-box="_routePosition.view_box">

                        <!-- Borders -->
                        <ProvinceVector
                            v-for="(province, uuid) in this._routePositionBorders"
                            :key="uuid"
                            :province="province"
                            @provinceClicked="extendTravelRoute"
                            :fill-color="borderColor(province)"
                            :highlight="provinceInRoute(province)"
                        >
                        </ProvinceVector>

                        <ProvinceVector :province="_routePosition" :highlight="true"></ProvinceVector>
                    </MapViewPort>
                </v-flex>
            </v-layout>
        </v-card>
    </v-flex>
</template>

<script>

    import { mapActions } from 'vuex'
    import { mapGetters } from 'vuex';

    import MapViewPort from "../../map/MapViewPort";
    import ProvinceVector from "../../map/ProvinceVector";
    import TravelRouteListItem from "../../map/TravelRouteListItem";

    export default {
        name: "TravelView",
        components: {
            TravelRouteListItem,
            ProvinceVector,
            MapViewPort
        },
        methods: {
            ...mapActions([
                'setCurrentLocation',
                'extendTravelRoute'
            ]),
            minimMapProvinceColor(province) {
                if (province.uuid === this._routePosition.uuid) {
                    return '#4ef542';
                } else if (this.provinceInRoute(province)
                    || province.uuid === this._currentLocation.uuid
                ) {
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
            }
        },

        computed: {
            ...mapGetters([
                '_currentLocation',
                '_routePosition',
                '_routePositionBorders',
                '_provinces',
                '_travelRoute'
            ]),
            oceanColor() {
                return '#000000';
            },
            routeList() {
                return this._travelRoute.reverse();
            }
        }
    }
</script>

<style scoped>

</style>
