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
            <template v-if="exploredProvince">
                <CardBlock :title="'Quests'">
                    <template v-if="exploredProvince.quests.length">
                        <CompactQuestPanel
                            v-for="(compactQuest, uuid) in exploredProvince.quests"
                            :compact-quest="compactQuest"
                            :key="uuid"
                        ></CompactQuestPanel>
                    </template>
                    <template v-else>
                        <EmptyNotifier :notification-text="emptyQuestsText"></EmptyNotifier>
                    </template>
                </CardBlock>
            </template>
            <template v-else>
                <v-row :justify="'center'" class="py-5">
                    <v-progress-circular indeterminate size="48"></v-progress-circular>
                </v-row>
            </template>
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

    export default {
        name: "ProvinceView",
        components: {
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
            // We need to watch province changes for when this component is reused to possible update exploredProvince
            province: function () {
                this.maybeUpdateExploredProvince();
            }
        },

        methods: {
            ...mapActions([
                'updateExploredProvince'
            ]),
            navigateToProvince(province) {
                province.goToRoute(this.$router, this.$route);
            },
            maybeUpdateExploredProvince() {
                if (! this.exploredProvince) {
                    this.updateExploredProvince(this.$route.params.provinceSlug)
                }
            }
        },

        mounted() {
            this.maybeUpdateExploredProvince();
        },

        computed: {
            ...mapGetters([
                '_provinceBySlug',
                '_provincesByUuids',
                '_continentByID',
                '_territoryByID',
                '_exploredProvinceByProvinceSlug'
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
            exploredProvince() {
                let provinceSlug = this.$route.params.provinceSlug;
                return this._exploredProvinceByProvinceSlug(provinceSlug);
            },
            emptyQuestsText() {
                return 'No quests located at ' + this.province.name;
            }
        }
    }
</script>

<style scoped>

</style>
