<template>
    <TwoColumnWideLayout>
        <template v-slot:header>
            <DisplayHeaderText :display-text="province.name"></DisplayHeaderText>
        </template>
        <template v-slot:column-one>
            <v-row no-gutters>
                <v-col cols="12" class="mb-3">
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
            <template v-if="mapProvince">
                <div class="mb-2">
                    <MapProvinceInfoCard :map-province="mapProvince"></MapProvinceInfoCard>
                </div>
            </template>
            <template v-else>
                <v-row justify="center" class="py-5">
                    <v-progress-circular indeterminate size="48"></v-progress-circular>
                </v-row>
            </template>
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

    import {mapActions} from 'vuex';
    import {mapGetters} from 'vuex';
    import MapViewPortWithControls from "../../../realm/MapViewPortWithControls";
    import TwoColumnWideLayout from "../../../layouts/TwoColumnWideLayout";
    import ProvincePanel from "../../../realm/ProvincePanel";
    import TerritoryPanel from "../../../realm/TerritoryPanel";
    import ContinentPanel from "../../../realm/ContinentPanel";
    import DisplayHeaderText from "../../../global/DisplayHeaderText";
    import CardBlock from "../../../global/CardBlock";
    import EmptyNotifier from "../../../global/EmptyNotifier";
    import CompactQuestPanel from "../../../realm/CompactQuestPanel";
    import MapProvinceInfoCard from "../../../realm/MapProvinceInfoCard";

    export default {
        name: "ProvinceView",
        components: {
            MapProvinceInfoCard,
            CompactQuestPanel,
            EmptyNotifier,
            CardBlock,
            DisplayHeaderText,
            ContinentPanel,
            TerritoryPanel,
            ProvincePanel,
            TwoColumnWideLayout,
            MapViewPortWithControls,
            ProvinceVector
        },
        watch: {
            // We need to watch province changes for when this component is reused to possible update mapProvince
            province: function () {
                this.maybeUpdateMapProvince();
            }
        },

        methods: {
            ...mapActions([
                'updateMapProvince'
            ]),
            navigateToProvince(province) {
                province.goToRoute(this.$router, this.$route);
            },
            maybeUpdateMapProvince() {
                if (! this.mapProvince) {
                    this.updateMapProvince(this.$route.params.provinceSlug)
                }
            }
        },

        mounted() {
            this.maybeUpdateMapProvince();
        },

        computed: {
            ...mapGetters([
                '_provinceBySlug',
                '_provincesByUuids',
                '_continentByID',
                '_territoryByID',
                '_mapProvinceByProvinceSlug'
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
            },
            mapProvince() {
                let provinceSlug = this.$route.params.provinceSlug;
                return this._mapProvinceByProvinceSlug(provinceSlug);
            },
            emptyQuestsText() {
                return 'No quests located at ' + this.province.name;
            }
        }
    }
</script>

<style scoped>

</style>
