<template>
    <v-container>
        <v-row>
            <v-col cols="8" offset="2" sm="6" offset-sm="3" offset-md="4" md="4">
                <v-row no-gutters justify="space-around">
                    <v-col cols="6" class="px-2">
                        <v-btn
                            block
                            :color="'primary'"
                            :to="travelRoute"
                        >Travel</v-btn>
                    </v-col>
                    <v-col cols="6" class="px-2">
                        <v-btn
                            block
                            :color="'primary'"
                            :to="exploreRoute"
                        >Explore</v-btn>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col cols="6" class="pa-1">
                <MapViewPort :view-box="_currentLocationProvince.viewBox">

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
            <v-col cols="6" class="pa-1">
                <MapViewPort :ocean-color="'#000'">

                    <!-- Borders -->
                    <ProvinceVector
                        v-for="(province, uuid) in provinces"
                        :key="uuid"
                        :province="province"
                        :fill-color="'#808080'"
                    >
                    </ProvinceVector>

                    <ProvinceVector :province="_currentLocationProvince" :fill-color="'#28bf5b'"></ProvinceVector>
                    <MapWindow :view-box="_currentLocationProvince.viewBox"></MapWindow>
                </MapViewPort>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import { mapGetters } from 'vuex';

    import ProvinceVector from "../../realm/ProvinceVector";
    import MapViewPort from "../../realm/MapViewPort";
    import MapWindow from "../../realm/MapWindow";

    export default {
        name: "CurrentLocation",
        components: {
            MapWindow,
            MapViewPort,
            ProvinceVector,
        },

        computed: {
            ...mapGetters([
                '_currentLocationProvince',
                '_provincesByUuids',
                '_provinces'
            ]),
            bordersCount() {
                return this._currentLocationProvince.borderUuids.length;
            },
            borders() {
                return this._provincesByUuids(this._currentLocationProvince.borderUuids);
            },
            provinces() {
                return this._provinces.filter((province) => province.uuid !== this._currentLocationProvince.uuid);
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
