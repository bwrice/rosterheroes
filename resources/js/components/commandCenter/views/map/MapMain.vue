<template>
    <v-col cols="12">
        <v-card>
            <v-row no-gutters>
                <v-col cols="5" class="pa-1">
                    <MapViewPort :tile="false" :view-box="_currentLocation.viewBox">

                        <!-- Borders -->
                        <ProvinceVector
                            v-for="(province, uuid) in borders"
                            :key="uuid"
                            :province="province"
                        >
                        </ProvinceVector>

                        <ProvinceVector :province="_currentLocation" :highlight="true"></ProvinceVector>
                    </MapViewPort>
                </v-col>
                <v-col cols="7" class="pa-1">
                    <v-row no-gutters>
                        <v-col cols="12">
                            <p>
                                Current Location: {{_currentLocation.name}} <br>
                                Borders: {{bordersCount}} <br>
                                Squads: {{_currentLocation.squads_count}} <br>
                                Quests: {{_currentLocation.quests_count}}
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

    // import {bordersMixin} from "../../../../mixins/bordersMixin";

    import MapViewPort from "../../map/MapViewPort";
    import ProvinceVector from "../../map/ProvinceVector";

    export default {
        name: "MapMain",
        components: {
            ProvinceVector,
            MapViewPort
        },

        // mixins: [
        //     bordersMixin
        // ],

        computed: {
            ...mapGetters([
                '_currentLocation',
                '_provincesByUuids'
            ]),
            bordersCount() {
                return this._currentLocation.borderUuids.length;
            },
            borders() {
                return this._provincesByUuids(this._currentLocation.borderUuids);
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
                return this._currentLocation;
            }
        }
    }
</script>

<style scoped>

</style>
