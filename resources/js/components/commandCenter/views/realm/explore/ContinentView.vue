<template>
    <v-container>
        <v-row>
            <v-col cols="12" lg="8" offset-lg="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <MapViewPort :view-box="continent.viewBox" :tile="false">
                            <ProvinceVector
                                v-for="(province, uuid) in provinces"
                                :key="uuid"
                                :province="province"
                                @provinceClicked="navigateToProvince"
                            ></ProvinceVector>
                        </MapViewPort>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';

    import ProvinceVector from "../../../realm/ProvinceVector";
    import Continent from "../../../../../models/Continent";
    import MapViewPort from "../../../realm/MapViewPort";

    export default {
        name: "ContinentView",
        components: {
            MapViewPort,
            ProvinceVector
        },

        methods: {
            navigateToProvince(province) {
                province.goToRoute(this.$router, this.$route);
            },
        },

        computed: {
            ...mapGetters([
                '_continentBySlug',
                '_provincesByContinentID'
            ]),
            continent() {
                let slug = this.$route.params.continentSlug;
                let continent = this._continentBySlug(slug);
                return continent ? continent : new Continent({});
            },
            provinces() {
                return this._provincesByContinentID(this.continent.id);
            }
        }
    }
</script>

<style scoped>

</style>
