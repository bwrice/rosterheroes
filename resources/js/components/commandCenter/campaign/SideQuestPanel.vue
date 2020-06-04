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
            <span class="subtitle-1 rh-op-90 font-weight-regular mx-1">
                {{sideQuest.name}}
            </span>
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
            <v-carousel
                :height="height"
                hide-delimiter-background
                show-arrows-on-hover
            >
                <v-carousel-item
                    v-for="(minion, uuid) in sideQuest.minions"
                    :key="uuid"
                >
                    <MinionPanel :minion="minion" :height="height"></MinionPanel>
                </v-carousel-item>
            </v-carousel>
        </v-row>
    </v-sheet>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import SideQuest from "../../../models/SideQuest";
    import MinionPanel from "../views/campaign/MinionPanel";
    import Quest from "../../../models/Quest";

    export default {
        name: "SideQuestPanel",
        components: {MinionPanel},
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
                expanded: false
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
