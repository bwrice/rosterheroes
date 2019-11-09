<template>
    <v-container>
        <v-row>
            <v-col cols="12" lg="8" offset-lg="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <MapViewPortWithControls :view-box="territory.viewBox">
                            <ProvinceVector
                                v-for="(province, uuid) in provinces"
                                :key="uuid"
                                :province="province"
                                :hoverable="true"
                                @provinceClicked="navigateToProvince"
                            ></ProvinceVector>
                        </MapViewPortWithControls>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';

    import ProvinceVector from "../../../realm/ProvinceVector";
    import ExploreMapCard from "../../../realm/ExploreMapCard";
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";

    export default {
        name: "TerritoryView",

        components: {
            MapViewPortWithControls,
            ExploreMapCard,
            ProvinceVector
        },

        methods: {
            navigateToProvince(province) {
                province.goToRoute(this.$router, this.$route);
            },
        },

        computed: {
            ...mapGetters([
                '_territoryBySlug',
                '_provincesByTerritoryID'
            ]),
            territory() {
                return this._territoryBySlug(this.$route.params.territorySlug);
            },
            provinces() {
                return this._provincesByTerritoryID(this.territory.id);
            }
        }
    }
</script>

<style scoped>

</style>
