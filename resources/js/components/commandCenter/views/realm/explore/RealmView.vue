<template>
    <v-container>
        <v-row>
            <v-col cols="12" lg="8" offset-lg="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <MapViewPortWithControls :view-box="viewBox" :tile="false">
                            <template v-if="mode === 'continents'">
                                <ContinentVector v-for="(continent, id) in this._continents" :key="id" :continent="continent"></ContinentVector>
                            </template>
                            <template v-else-if="mode === 'territories'">
                                <TerritoryVector v-for="(territory, id) in this._territories" :key="id" :territory="territory"></TerritoryVector>
                            </template>
                            <template v-else-if="mode === 'provinces'">
                                <ProvinceVector
                                    v-for="(province, uuid) in this._provinces"
                                    :key="uuid"
                                    :province="province"
                                    :hoverable="true"
                                    @provinceClicked="province.goToRoute($router, $route)"
                                ></ProvinceVector>
                            </template>
                        </MapViewPortWithControls>
                    </v-col>
                </v-row>
                <v-row no-gutters class="py-2">
                    <v-col cols="8" offset="2" md="12" offset-md="0">
                        <v-row no-gutters>
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
            </v-col>
        </v-row>
    </v-container>
<!--    <ExploreMapCard>-->
<!--        <template slot="footer-content">-->
<!--            <v-row no-gutters>-->
<!--                <v-col cols="12">-->
<!--                    <v-chip class="ma-1"-->
<!--                            :input-value="inContinentMode"-->
<!--                            @click="setRealmMapMode('continent')"-->
<!--                            filter-->
<!--                            filter-icon="mdi-eye"-->
<!--                            label-->
<!--                    >-->
<!--                        Continents-->
<!--                    </v-chip>-->
<!--                    <v-chip class="ma-1"-->
<!--                            :input-value="inTerritoryMode"-->
<!--                            @click="setRealmMapMode('territory')"-->
<!--                            filter-->
<!--                            filter-icon="mdi-eye"-->
<!--                            label-->
<!--                    >-->
<!--                        Territories-->
<!--                    </v-chip>-->
<!--                </v-col>-->
<!--            </v-row>-->
<!--        </template>-->
<!--    </ExploreMapCard>-->
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

    export default {
        name: "RealmView",
        components: {
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
