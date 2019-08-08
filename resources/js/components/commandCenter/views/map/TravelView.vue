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
                            <h5>Current Route</h5>
                            <v-sheet tile :color="routeItemColor(_currentLocation)" class="pa-1">
                                {{this._currentLocation.name}}
                            </v-sheet>
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
    import TravelRouteBorder from "../../map/TravelRouteBorder";

    export default {
        name: "TravelView",
        components: {
            TravelRouteBorder,
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
                    return '#035afc';
                }
                return '#dedede';
            },
            routeItemColor(province) {
                if (province.uuid === this._routePosition.uuid) {
                    return '#035afc';
                }
                return 'success';
            }
        },

        computed: {
            ...mapGetters([
                '_currentLocation',
                '_routePosition',
                '_routePositionBorders',
                '_provinces'
            ]),
            oceanColor() {
                return '#000000';
            }
        }
    }
</script>

<style scoped>

</style>
