<template>
    <v-flex class="xs12">
        <v-card class="pa-1">
            <v-layout>
                <v-flex class="xs5">
                    <MapViewPort :tile="false" :view-box="_currentLocation.view_box">

                        <!-- Borders -->
                        <ProvinceVector
                            v-for="(province, uuid) in this._currentLocation.borders"
                            :key="uuid"
                            :province="province"
                        >
                        </ProvinceVector>

                        <ProvinceVector :province="_currentLocation" :highlight="true"></ProvinceVector>
                    </MapViewPort>
                </v-flex>
                <v-flex class="xs7 px-1">
                    <p>
                        Current Location: {{_currentLocation.name}} <br>
                        Borders: {{bordersCount}} <br>
                        Squads: {{_currentLocation.squads_count}} <br>
                        Quests: {{_currentLocation.quests_count}}
                    </p>
                    <v-layout>
                        <v-flex class="xs12 md8 offset-md2">
                            <v-btn
                                :color="'primary'"
                                :to="travelRoute"
                            >Travel</v-btn>
                            <v-btn
                                :color="'primary'"
                                :to="exploreRoute"
                            >Explore</v-btn>
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
    import Squad from "../../../../models/Squad";
    import Province from "../../../../models/Province";
    import ProvinceVector from "../../map/ProvinceVector";

    export default {
        name: "MapMain",
        components: {
            ProvinceVector,
            MapViewPort
        },

        async mounted() {
            let squadSlug = this.$route.params.squadSlug;
            let squad = new Squad({slug: squadSlug});
            let currentLocation = await Province.custom(squad, 'current-location').$first();
            this.setCurrentLocation(currentLocation);
        },

        methods: {
            ...mapActions([
                'setCurrentLocation'
            ])
        },

        computed: {
            ...mapGetters([
                '_currentLocation'
            ]),
            bordersCount() {
                return this._currentLocation.borders.length;
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
            }
        }
    }
</script>

<style scoped>

</style>
