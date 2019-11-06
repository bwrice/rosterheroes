<template>
    <v-col cols="12">
        <v-card>
            <v-row no-gutters>
                <v-col cols="5" class="pa-1">
                    <MapViewPort :tile="false" :view-box="_currentLocationProvince.viewBox">

                        <!-- Borders -->
                        <ProvinceVector
                            v-for="(province, uuid) in borders"
                            :key="uuid"
                            :province="province"
                        >
                        </ProvinceVector>

                        <ProvinceVector :province="_currentLocationProvince" :highlight="true"></ProvinceVector>
                    </MapViewPort>
                </v-col>
                <v-col cols="7" class="pa-1">
                    <v-row no-gutters>
                        <v-col cols="12">
                            <p>
                                Current Location: {{_currentLocationProvince.name}} <br>
                                Borders: {{bordersCount}} <br>
                            </p>
                        </v-col>
                    </v-row>
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-btn
                                :color="'primary'"
                                :to="travelRoute"
                            >Travel</v-btn>
                            <v-btn
                                :color="'primary'"
                                :to="exploreRoute"
                            >Explore</v-btn>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-card>
    </v-col>
</template>

<script>

    import { mapGetters } from 'vuex';

    import MapViewPort from "../../map/MapViewPort";
    import ProvinceVector from "../../map/ProvinceVector";

    export default {
        name: "MapMain",
        components: {
            ProvinceVector,
            MapViewPort
        },

        computed: {
            ...mapGetters([
                '_currentLocationProvince',
                '_provincesByUuids'
            ]),
            bordersCount() {
                return this._currentLocationProvince.borderUuids.length;
            },
            borders() {
                return this._provincesByUuids(this._currentLocationProvince.borderUuids);
            },
            exploreRoute() {
                return {
                    name: 'explore-realm',
                    params: {
                        squadSlug: this.$route.params.squadSlug
                    }
                }
            },
            travelRoute() {
                return {
                    name: 'travel',
                    params: {
                        squadSlug: this.$route.params.squadSlug
                    }
                }
            },
            // needed for borders mixin
            province() {
                return this._currentLocationProvince;
            }
        }
    }
</script>

<style scoped>

</style>
