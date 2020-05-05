<template>
    <TwoColumnWideLayout>
        <template v-slot:header>
            <DisplayHeaderText :display-text="territory.name"></DisplayHeaderText>
        </template>
        <template v-slot:column-one>
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
        </template>
        <template v-slot:column-two>
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="title font-weight-thin">PROVINCES</span>
                </v-col>
                <v-col cols="12">
                    <PaginationBlock
                        :items-per-page="6"
                        :items="provinces"
                    >
                        <template v-slot:default="slotProps">
                            <ProvincePanel :province="slotProps.item"></ProvincePanel>
                        </template>
                    </PaginationBlock>
                </v-col>
            </v-row>
        </template>
    </TwoColumnWideLayout>
</template>

<script>

    import {mapGetters} from 'vuex';

    import ProvinceVector from "../../../realm/ProvinceVector";
    import ExploreMapCard from "../../../realm/ExploreMapCard";
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";
    import TwoColumnWideLayout from "../../../layouts/TwoColumnWideLayout";
    import PaginationBlock from "../../../global/PaginationBlock";
    import ProvincePanel from "../../../realm/ProvincePanel";
    import DisplayHeaderText from "../../../global/DisplayHeaderText";

    export default {
        name: "TerritoryView",

        components: {
            DisplayHeaderText,
            ProvincePanel,
            PaginationBlock,
            TwoColumnWideLayout,
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
