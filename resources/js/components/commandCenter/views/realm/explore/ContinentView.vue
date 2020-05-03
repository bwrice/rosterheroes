<template>
    <TwoColumnWideLayout>
        <template v-slot:column-one>
            <v-row no-gutters>
                <v-col cols="12">
                    <MapViewPortWithControls :view-box="continent.viewBox" :tile="false">
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
        </template>
        <template v-slot:column-two>
            <!-- TODO -->
        </template>
    </TwoColumnWideLayout>
</template>

<script>

    import {mapGetters} from 'vuex';

    import ProvinceVector from "../../../realm/ProvinceVector";
    import Continent from "../../../../../models/Continent";
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";
    import TwoColumnWideLayout from "../../../layouts/TwoColumnWideLayout";

    export default {
        name: "ContinentView",
        components: {
            TwoColumnWideLayout,
            MapViewPortWithControls,
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
