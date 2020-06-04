<template>
    <v-sheet color="#466661" class="my-1">
        <v-row no-gutters class="pa-1" justify="center" align="center">
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-chip
                        label
                        color="rgba(0,0,0,.25)"
                        v-on="on"
                    >
                        {{sideQuest.difficulty}}
                    </v-chip>
                </template>
                <span>difficulty</span>
            </v-tooltip>
            <v-col cols="5 subtitle-1 rh-op-90 font-weight-regular mx-1 text-truncate">
                {{sideQuest.name}}
            </v-col>
            <div class="flex flex-grow-1"></div>
            <v-btn
                v-if="hasSideQuest"
                color="error"
                class="mx-1"
                @click="handleLeaveSideQuestClicked"
            >
                Leave
            </v-btn>
            <v-btn
                v-else
                color="primary"
                class="mx-1"
                :disabled="! canJoinSideQuest"
                @click="handleJoinSideQuestClicked"
            >
                Join
            </v-btn>
            <v-btn @click="expanded = ! expanded"
                   fab
                   dark
                   x-small
                   color="rgba(0, 0, 0, .4)"
            >
                <v-icon v-if="expanded">expand_less</v-icon>
                <v-icon v-else>expand_more</v-icon>
            </v-btn>
        </v-row>
        <v-row no-gutters v-if="expanded">
            <v-col cols="12" class="pa-1">
                <v-card
                    color="#32343d"
                >
                    <v-card-title>
                        <span class="text-center rh-op-85">
                            {{sideQuest.name}}
                        </span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-tabs
                        v-model="tab"
                        mobile-break-point="10"
                        centered
                        background-color="#32343d"
                        color="#b3c9c3"
                        slider-color="primary"
                    >
                        <v-tab
                            v-for="(minion, uuid) in sideQuest.minions"
                            :key="uuid"
                        >
                            {{ minion.name }}
                        </v-tab>
                    </v-tabs>
                    <v-tabs-items v-model="tab" style="background-color: transparent">
                        <SideQuestMinionTab
                            v-for="(minion, uuid) in sideQuest.minions"
                            :key="uuid"
                            :minion="minion"
                        ></SideQuestMinionTab>
                    </v-tabs-items>
                </v-card>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import SideQuest from "../../../models/SideQuest";
    import SideQuestMinionTab from "../views/campaign/SideQuestMinionTab";
    import Quest from "../../../models/Quest";

    export default {
        name: "SideQuestPanel",
        components: {SideQuestMinionTab},
        props: {
            sideQuest: {
                type: SideQuest,
                required: true
            },
            quest: {
                type: Quest,
                required: true
            },
            height: {
                type: Number,
                default: 300
            }
        },
        data() {
            return {
                pending: false,
                expanded: false,
                tab: null
            }
        },
        computed: {
            ...mapGetters([
                '_matchingCampaignStop',
                '_squadSideQuestUuids',
                '_squad'
            ]),
            canJoinSideQuest() {
                let maxReached = false;
                if (this.campaignStop) {
                    maxReached = this.campaignStop.sideQuestUuids.length >= this._squad.sideQuestsPerQuest;
                }
                return (this.campaignStop && ! this.hasSideQuest && ! this.pending && ! maxReached);
            },
            campaignStop() {
                return this._matchingCampaignStop(this.quest.uuid);
            },
            hasSideQuest() {
                let localSideQuest = this.sideQuest;
                let matchingSideQuest = this._squadSideQuestUuids.find(uuid => uuid === localSideQuest.uuid);
                return matchingSideQuest !== undefined;
            }
        },
        methods: {
            ...mapActions([
                'joinSideQuest',
                'leaveSideQuest'
            ]),
            async handleJoinSideQuestClicked() {
                this.pending = true;
                await this.joinSideQuest({
                    campaignStop: this.campaignStop,
                    sideQuest: this.sideQuest
                });
                this.pending = false;
            },
            async handleLeaveSideQuestClicked() {
                this.pending = true;
                await this.leaveSideQuest({
                    campaignStop: this.campaignStop,
                    sideQuest: this.sideQuest
                });
                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>
