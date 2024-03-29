<template>
    <TwoColumnWideLayout>
        <template v-slot:header>
            <DisplayHeaderText :display-text="continent.name"></DisplayHeaderText>
        </template>
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
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="title font-weight-thin">PROVINCES</span>
                </v-col>
                <v-col cols="12">
                    <ProvincePaginationBlock :provinces="provinces"></ProvincePaginationBlock>
                </v-col>
            </v-row>
        </template>
    </TwoColumnWideLayout>
</template>

<script>

    import {mapGetters} from 'vuex';

    import ProvinceVector from "../../../realm/ProvinceVector";
    import Continent from "../../../../../models/Continent";
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";
    import TwoColumnWideLayout from "../../../layouts/TwoColumnWideLayout";
    import PaginationBlock from "../../../global/PaginationBlock";
    import ProvincePanel from "../../../realm/ProvincePanel";
    import DisplayHeaderText from "../../../global/DisplayHeaderText";
    import ProvincePaginationBlock from "../../../realm/ProvincePaginationBlock";

    export default {
        name: "ContinentView",
        components: {
            ProvincePaginationBlock,
            DisplayHeaderText,
            ProvincePanel,
            PaginationBlock,
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
