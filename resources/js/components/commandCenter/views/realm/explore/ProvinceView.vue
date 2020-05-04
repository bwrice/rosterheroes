<template>
    <TwoColumnWideLayout>
        <template v-slot:column-one>
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
        </template>
        <template v-slot:column-two>
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="title font-weight-thin">BORDERS</span>
                </v-col>
                <v-col cols="12">
                    <PaginationBlock
                        :items-per-page="6"
                        :items="borders"
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

    import ProvinceVector from "../../../realm/ProvinceVector";
    import ExploreMapCard from "../../../realm/ExploreMapCard";

    import {mapGetters} from 'vuex';
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";
    import TwoColumnWideLayout from "../../../layouts/TwoColumnWideLayout";
    import PaginationBlock from "../../../global/PaginationBlock";
    import ProvincePanel from "../../../realm/ProvincePanel";

    export default {
        name: "ProvinceView",
        components: {
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
