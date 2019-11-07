<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="0">
                <v-row no-gutters justify="space-around">
                    <v-col cols="6" class="px-2">
                        <v-btn
                            block
                            large
                            :color="'primary'"
                            :to="travelRoute"
                        >Travel</v-btn>
                    </v-col>
                    <v-col cols="6" class="px-2">
                        <v-btn
                            block
                            large
                            :color="'primary'"
                            :to="exploreRoute"
                        >Explore</v-btn>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12" class="py-3">
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
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import { mapGetters } from 'vuex';

    import MapViewPort from "../../realm/MapViewPort";
    import ProvinceVector from "../../realm/ProvinceVector";

    export default {
        name: "CurrentLocation",
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
