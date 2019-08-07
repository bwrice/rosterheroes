<template>
    <v-flex class="xs12">
        <v-card>
            <v-layout>
                <v-flex class="xs5">
                    <v-layout>
                        <v-flex class="xs12">
                            <MapViewPort :ocean-color="oceanColor">

                                <!-- Borders -->
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
                        </v-flex>
                    </v-layout>
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

    export default {
        name: "TravelView",
        components: {
            ProvinceVector,
            MapViewPort
        },
        methods: {
            ...mapActions([
                'setCurrentLocation'
            ]),
            minimMapProvinceColor: function(province) {
                if (province.uuid === this._currentLocation.uuid) {
                    return '#035afc';
                }
                return '#dedede';
            }
        },

        computed: {
            ...mapGetters([
                '_currentLocation',
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
