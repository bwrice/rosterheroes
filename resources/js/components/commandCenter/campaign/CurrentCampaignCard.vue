<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">CURRENT CAMPAIGN</span>
        </v-col>
        <v-col cols="12" v-if="_currentCampaign">
            <v-sheet :color="'#5c707d'" class="my-1 rounded">
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row class="px-2">
                            <v-col cols="6">
                                <v-row align="center" justify="center">
                                    <span class="subtitle-1 font-weight-light">CONTINENT: {{continent.name}}</span>
                                </v-row>
                            </v-col>
                            <v-col cols="6">
                                <v-row align="center" justify="center">
                                    <span class="subtitle-1 font-weight-light">QUESTS: {{questRatioText}}</span>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="12" class="px-1">
                        <MapViewPort :view-box="continent.viewBox" :tile="true" :ocean-color="'#000'" :rounded-size="'none'">
                            <ProvinceVector
                                v-for="(province, uuid) in provincesForContinent"
                                :key="uuid"
                                :province="province"
                                :fill-color="continentProvinceFillColor(province)"
                            >
                            </ProvinceVector>
                        </MapViewPort>
                    </v-col>
                    <CampaignStopCard
                        v-for="(campaignStop, uuid) in _currentCampaign.campaignStops"
                        :key="uuid"
                        :campaign-stop="campaignStop"
                        @leaveQuestClicked="handleLeaveQuestClicked"
                    >
                    </CampaignStopCard>
                </v-row>
            </v-sheet>
        </v-col>
        <v-col col="12" v-else>
            <v-sheet color="rgba(255,255,255, 0.25)">
                <v-row no-gutters class="pa-2" justify="center" align="center">
                    <span class="rh-op-70 subtitle-1">No campaign for the current week. Join quests to start a campaign.</span>
                </v-row>
            </v-sheet>
        </v-col>
        <v-dialog
            v-model="leaveQuestDialog"
            max-width="500"
        >
            <v-sheet
            >
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row no-gutters justify="center" class="px-2 pt-1">
                            <span class="title font-weight-bolder">{{leaveQuestTitle}}</span>
                        </v-row>
                        <v-row no-gutters justify="center" class="px-3 py-1">
                            <p class="font-weight-regular">{{leaveQuestMessage}}</p>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row no-gutters justify="end" align="center" class="pa-2">
                    <v-btn
                        outlined
                        color="error"
                        @click="leaveQuestDialog = false"
                        class="mx-1"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        color="success"
                        class="mx-1"
                        @click="confirmLeaveQuest"
                        :disabled="pendingLeaveQuest"
                    >
                        Leave Quest
                    </v-btn>
                </v-row>
            </v-sheet>
        </v-dialog>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import MapViewPort from "../realm/MapViewPort";
    import Continent from "../../../models/Continent";
    import ContinentVector from "../realm/ContinentVector";
    import ProvinceVector from "../realm/ProvinceVector";
    import CampaignStopCard from "./CampaignStopCard";
    import CampaignStop from "../../../models/CampaignStop";
    import {leaveQuest} from "../../../api/squadApi";
    export default {
        name: "CurrentCampaignCard",
        components: {CampaignStopCard, ProvinceVector, ContinentVector, MapViewPort},
        data() {
            return {
                leaveQuestDialog: false,
                campaignStopToLeave: new CampaignStop({}),
                pendingLeaveQuest: false
            }
        },
        methods: {
            ...mapActions([
                'leaveQuest'
            ]),
            continentProvinceFillColor(province) {
                let provinceInCampaignStops = this._currentCampaign.campaignStops.find(campaignStop => campaignStop.provinceUuid === province.uuid);
                return provinceInCampaignStops ? '#28bf5b' : '#808080';
            },
            handleLeaveQuestClicked(campaignStop) {
                this.campaignStopToLeave = campaignStop;
                this.leaveQuestDialog = true;
            },
            async confirmLeaveQuest() {
                this.pendingLeaveQuest = true;
                await this.leaveQuest({
                    questUuid: this.campaignStopToLeave.questUuid,
                    questName: this.campaignStopToLeave.compactQuest.name
                });
                this.leaveQuestDialog = false;
                this.pendingLeaveQuest = false;
            }
        },
        computed: {
            ...mapGetters([
                '_currentCampaign',
                '_continentByID',
                '_provincesByContinentID',
                '_provinceByUuid',
                '_squad'
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
            questRatioText() {
                return '(' + this._currentCampaign.campaignStops.length + '/' + this._squad.questsPerWeek + ')';
            },
            leaveQuestTitle() {
                return 'Leave ' + this.campaignStopToLeave.compactQuest.name + '?';
            },
            leaveQuestMessage() {
                let message = 'Are you sure you want to leave the quest, ';
                message += this.campaignStopToLeave.compactQuest.name + '? ';
                message += 'You will have to travel back to the province of ';
                message += this._provinceByUuid(this.campaignStopToLeave.provinceUuid).name;
                message += ' if you want to add the quest back to your campaign';
                return message;
            }
        }
    }
</script>

<style scoped>

</style>
