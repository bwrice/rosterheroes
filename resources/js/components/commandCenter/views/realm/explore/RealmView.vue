<template>
    <TwoColumnWideLayout>
        <template v-slot:header>
            <DisplayHeaderText :display-text="'Explore the Realm'"></DisplayHeaderText>
        </template>
        <template v-slot:column-one>
            <v-row no-gutters>
                <v-col cols="12">
                    <MapViewPortWithControls :view-box="viewBox" :tile="false">
                        <template v-if="mode === 'continents'">
                            <ContinentVector v-for="(continent, id) in _continents" :key="id" :continent="continent"></ContinentVector>
                        </template>
                        <template v-else-if="mode === 'territories'">
                            <TerritoryVector v-for="(territory, id) in _territories" :key="id" :territory="territory"></TerritoryVector>
                        </template>
                        <template v-else-if="mode === 'provinces'">
                            <ProvinceVector
                                v-for="(province, uuid) in _provinces"
                                :key="uuid"
                                :province="province"
                                :hoverable="true"
                                @provinceClicked="province.goToRoute($router, $route)"
                            ></ProvinceVector>
                        </template>
                    </MapViewPortWithControls>
                </v-col>
                <v-col cols="8" offset="2" md="12" offset-md="0">
                    <v-row no-gutters class="py-2">
                        <v-col cols="12" md="4" class="pa-1">
                            <v-btn
                                block
                                @click="mode = 'continents'"
                                :color="mode === 'continents' ? 'accent darken-1' : 'primary'"
                                :depressed="mode === 'continents'"
                            >
                                Continents
                            </v-btn>
                        </v-col>
                        <v-col cols="12" md="4" class="pa-1">
                            <v-btn
                                block
                                @click="mode = 'territories'"
                                :color="mode === 'territories' ? 'accent darken-1' : 'primary'"
                                :depressed="mode === 'territories'"
                            >
                                Territories
                            </v-btn>
                        </v-col>
                        <v-col cols="12" md="4" class="pa-1">
                            <v-btn
                                block
                                @click="mode = 'provinces'"
                                :color="mode === 'provinces' ? 'accent darken-1' : 'primary'"
                                :depressed="mode === 'provinces'"
                            >
                                Provinces
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </template>
        <template v-slot:column-two>
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="title font-weight-thin">CONTINENTS</span>
                </v-col>
                <v-col cols="12">
                    <PaginationBlock
                        :items-per-page="4"
                        :items="_continents"
                    >
                        <template v-slot:default="slotProps">
                            <ContinentPanel :continent="slotProps.item"></ContinentPanel>
                        </template>
                    </PaginationBlock>
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="title font-weight-thin">TERRITORIES</span>
                </v-col>
                <v-col cols="12">
                    <PaginationBlock
                        :items-per-page="4"
                        :items="_territories"
                    >
                        <template v-slot:default="slotProps">
                            <TerritoryPanel :territory="slotProps.item"></TerritoryPanel>
                        </template>
                    </PaginationBlock>
                </v-col>
            </v-row>
        </template>
    </TwoColumnWideLayout>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ContinentVector from "../../../realm/ContinentVector";
    import TerritoryVector from "../../../realm/TerritoryVector";
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";
    import MapControls from "../../../realm/MapControls";
    import ExploreMapCard from "../../../realm/ExploreMapCard";
    import ProvinceVector from "../../../realm/ProvinceVector";
    import ViewBox from "../../../../../models/ViewBox";
    import TwoColumnWideLayout from "../../../layouts/TwoColumnWideLayout";
    import PaginationBlock from "../../../global/PaginationBlock";
    import TerritoryPanel from "../../../realm/TerritoryPanel";
    import ContinentPanel from "../../../realm/ContinentPanel";
    import DisplayHeaderText from "../../../global/DisplayHeaderText";

    export default {
        name: "RealmView",
        components: {
            DisplayHeaderText,
            ContinentPanel,
            TerritoryPanel,
            PaginationBlock,
            TwoColumnWideLayout,
            ProvinceVector,
            ExploreMapCard,
            MapControls,
            MapViewPortWithControls,
            TerritoryVector,
            ContinentVector
        },

        data: function() {
            return {
                mode: 'continents',
                viewBox: new ViewBox({
                    panX: 0,
                    panY: 0,
                    zoomX: 315,
                    zoomY: 240
                })
            }
        },
        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents'
            ])
        }
    }
</script>

<style scoped>

</style>
