<template>
    <ClickableSheet :classes-object="{'my-1': true, 'pa-1': true}" @click="navigateToHistoricCampaign">
        <v-row no-gutters justify="center" align="center">
            <v-col cols="2">
                <MapViewPort :view-box="continent.viewBox" :tile="false">
                    <ProvinceVector
                        v-for="(province, uuid) in provinces"
                        :key="uuid"
                        :province="province"
                        :fill-color="continent.color"
                    ></ProvinceVector>
                </MapViewPort>
            </v-col>
            <v-col cols="10">
                <v-row no-gutters justify="center" align="center">
                    <span class="text-h6 text-md-h5 rh-op-90">{{historicCampaign.description}}</span>
                </v-row>
            </v-col>
        </v-row>
    </ClickableSheet>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import HistoricCampaign from "../../../models/HistoricCampaign";
    import MapViewPort from "../realm/MapViewPort";
    import ProvinceVector from "../realm/ProvinceVector";
    import Continent from "../../../models/Continent";
    import ClickableSheet from "../../shared/ClickableSheet";

    export default {
        name: "HistoricCampaignPanel",
        components: {ClickableSheet, ProvinceVector, MapViewPort},
        props: {
            historicCampaign: {
                type: HistoricCampaign,
                required: true
            }
        },
        methods: {
            ...mapActions([
                'updateFocusedCampaign'
            ]),
            navigateToHistoricCampaign() {
                this.updateFocusedCampaign(this.historicCampaign);
                this.$router.push({
                    name: 'historic-campaign',
                    params: {
                        campaignUuid: this.historicCampaign.uuid
                    }
                });
            }
        },

        computed: {
            ...mapGetters([
                '_continentByID',
                '_provincesByContinentID'
            ]),
            continent() {
                let continent = this._continentByID(this.historicCampaign.continentID);
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
