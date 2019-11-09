<template>
    <v-container>
        <v-row>
            <v-col cols="12" lg="8" offset-lg="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <MapViewPortWithControls :view-box="province.viewBox" :tile="false">
                            <!-- Borders -->
                            <ProvinceVector
                                v-for="(province, uuid) in borders"
                                :key="uuid"
                                :province="province"
                                :hoverable="true"
                                @provinceClicked="navigateToProvince"
                            >
                            </ProvinceVector>

                            <!-- Province -->
                            <ProvinceVector :province="province" :highlight="true"></ProvinceVector>
                        </MapViewPortWithControls>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import ProvinceVector from "../../../realm/ProvinceVector";
    import ExploreMapCard from "../../../realm/ExploreMapCard";

    import {mapGetters} from 'vuex';
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";

    export default {
        name: "ProvinceView",
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
                '_provinceBySlug',
                '_provincesByUuids'
            ]),
            province() {
                return this._provinceBySlug(this.$route.params.provinceSlug);
            },
            borders() {
                return this._provincesByUuids(this.province.borderUuids);
            }
        }
    }
</script>

<style scoped>

</style>
