<template>
    <v-container>
        <v-row justify="center" class="my-2">
            <span class="headline">{{campaignStopResult.questName}}</span>
        </v-row>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <CardSection :title="'SIDE QUEST RESULTS'">
                    <SideQuestPanel
                        v-for="(sideQuestResult, uuid) in campaignStopResult.sideQuestResults"
                        :key="uuid"
                        :side-quest="sideQuestResult.sideQuest"
                    >
                        <template v-slot:action>
                            <v-btn
                                color="primary"
                                class="mx-1"
                                @click="loadSideQuestResult(sideQuestResult)"
                                :disabled="focusedSideQuestResultUuid === sideQuestResult.uuid"
                                href="#replay"
                            >
                                replay
                            </v-btn>
                        </template>
                    </SideQuestPanel>
                </CardSection>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <CardSection :title="'SIDE QUEST RESULT REPLAY'" id="replay">
                    <template v-if="! focusedSideQuestResult">
                        <v-sheet color="rgba(255,255,255, 0.25)">
                            <v-row no-gutters class="pa-2" justify="center" align="center">
                                <span class="rh-op-70 subtitle-1">Click replay on a side-quest-result</span>
                            </v-row>
                        </v-sheet>
                    </template>
                    <template v-else-if="pending">
                        <LoadingOverlay :show-overlay="pending"></LoadingOverlay>
                    </template>
                    <template v-else>
                        <SideQuestResultReplay
                            :side-quest-result="focusedSideQuestResult"
                            :side-quest-events="sideQuestEvents"
                        >
                        </SideQuestResultReplay>
                    </template>
                </CardSection>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import SideQuestPanel from "../../campaign/SideQuestPanel";
    import CardSection from "../../global/CardSection";
    import CombatEvent from "../../../../models/CombatEvent";
    import LoadingOverlay from "../../global/LoadingOverlay";
    import SideQuestResultReplay from "../../campaign/SideQuestResultReplay";
    export default {
        name: "QuestResultView",
        components: {SideQuestResultReplay, LoadingOverlay, CardSection, SideQuestPanel},
        data() {
            return {
                focusedSideQuestResult: null,
                sideQuestEvents: [],
                pending: false,
            }
        },
        methods: {
            async loadSideQuestResult(sideQuestResult) {
                this.focusedSideQuestResult = sideQuestResult;
                this.pending = true;
                let response = await axios.get('/api/v1/side-quest-results/' + sideQuestResult.uuid + '/events');
                this.sideQuestEvents = response.data.data.map((sideQuestEvent) => new CombatEvent(sideQuestEvent));
                this.pending = false;
            }
        },
        computed: {
            ...mapGetters([
                '_campaignStopResultByUuid',
            ]),
            campaignStopResult() {
                return this._campaignStopResultByUuid(this.$route.params.campaignStopUuid);
            },
            focusedSideQuestResultUuid() {
                if (this.focusedSideQuestResult) {
                    return this.focusedSideQuestResult.uuid;
                }
                return null;
            }
        }
    }
</script>

<style scoped>

</style>
