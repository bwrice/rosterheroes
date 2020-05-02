<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">CURRENT CAMPAIGN</span>
        </v-col>
        <template v-if="_currentCampaign">
            <v-col cols="12">
                <MapViewPort :view-box="continent.viewBox" :tile="true" :ocean-color="'#000'">
                    <ProvinceVector
                        v-for="(province, uuid) in provincesForContinent"
                        :key="uuid"
                        :province="province"
                        :fill-color="continentProvinceFillColor(province)"
                    >
                    </ProvinceVector>
                </MapViewPort>
            </v-col>
        </template>
        <template v-else>
            <v-col col="12">
                <v-sheet color="rgba(255,255,255, 0.25)">
                    <v-row no-gutters class="pa-2" justify="center" align="center">
                        <span class="rh-op-70 subtitle-1">No campaign for the current week. Join quests to start a campaign.</span>
                    </v-row>
                </v-sheet>
            </v-col>
        </template>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import MapViewPort from "../realm/MapViewPort";
    import Continent from "../../../models/Continent";
    import ContinentVector from "../realm/ContinentVector";
    import ProvinceVector from "../realm/ProvinceVector";
    export default {
        name: "CurrentCampaignCard",
        components: {ProvinceVector, ContinentVector, MapViewPort},
        methods: {
            continentProvinceFillColor(province) {
                let provinceInCampaignStops = this._currentCampaign.campaignStops.find(campaignStop => campaignStop.provinceUuid === province.uuid);
                return provinceInCampaignStops ? '#28bf5b' : '#808080';
            }
        },
        computed: {
            ...mapGetters([
                '_currentCampaign',
                '_continentByID',
                '_provincesByContinentID'
            ]),
            continent() {
                if (this._currentCampaign) {
                    return this._continentByID(this._currentCampaign.continentID)
                }
                return new Continent({});
            },
            provincesForContinent() {
                return this._provincesByContinentID(this.continent.id);
            },
        }
    }
</script>

<style scoped>

</style>
