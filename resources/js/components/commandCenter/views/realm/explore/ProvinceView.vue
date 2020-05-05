<template>
    <TwoColumnWideLayout>
        <template v-slot:header>
            <DisplayHeaderText :display-text="province.name"></DisplayHeaderText>
        </template>
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
                    <span class="title font-weight-thin">CONTINENT: {{continent.name}}</span>
                </v-col>
                <v-col cols="12">
                    <ContinentPanel :continent="continent"></ContinentPanel>
                </v-col>
                <v-col cols="12">
                    <span class="title font-weight-thin">TERRITORY: {{territory.name}}</span>
                </v-col>
                <v-col cols="12">
                    <TerritoryPanel :territory="territory"></TerritoryPanel>
                </v-col>
                <v-col cols="12">
                    <span class="title font-weight-thin">BORDERS ({{borders.length}})</span>
                </v-col>
                <v-col cols="12">
                    <ProvincePanel
                        v-for="(province, uuid) in borders"
                        :province="province"
                        :key="uuid"
                    ></ProvincePanel>
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
    import ProvincePanel from "../../../realm/ProvincePanel";
    import TerritoryPanel from "../../../realm/TerritoryPanel";
    import ContinentPanel from "../../../realm/ContinentPanel";
    import DisplayHeaderText from "../../../global/DisplayHeaderText";

    export default {
        name: "ProvinceView",
        components: {
            DisplayHeaderText,
            ContinentPanel,
            TerritoryPanel,
            ProvincePanel,
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
                '_provincesByUuids',
                '_continentByID',
                '_territoryByID',
            ]),
            province() {
                return this._provinceBySlug(this.$route.params.provinceSlug);
            },
            borders() {
                return this._provincesByUuids(this.province.borderUuids);
            },
            continent() {
                return this._continentByID(this.province.continentID);
            },
            territory() {
                return this._territoryByID(this.province.territoryID);
            }
        }
    }
</script>

<style scoped>

</style>
