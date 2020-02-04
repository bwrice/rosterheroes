<template>
    <v-sheet color="#5c707d" class="pa-2">
        <v-row no-gutters class="py-2" justify="space-between" align="center">
            <span class="title rh-op-85 font-weight-regular">
                {{sideQuest.name}}
            </span>
            <v-chip
                label
                color="rgba(0,0,0,.25)"
            >
                {{sideQuest.difficulty}}
            </v-chip>
        </v-row>
        <v-row no-gutters>
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
        <v-row no-gutters justify="end">
            <v-btn
                v-if="hasSideQuest"
                color="error"
                class="mt-2"
                @click="handleLeaveSideQuestClicked"
            >
                Join Side Quest
            </v-btn>
            <v-btn
                v-else
                color="primary"
                class="mt-2"
                :disabled="! canJoinSideQuest"
                @click="handleJoinSideQuestClicked"
            >
                Leave Side Quest
            </v-btn>
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
        name: "SideQuestCard",
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
                pending: false
            }
        },
        computed: {
            ...mapGetters([
                '_matchingCampaignStop',
                '_squadSideQuestUuids'
            ]),
            canJoinSideQuest() {
                return (this.campaignStop && ! this.hasSideQuest && ! this.pending);
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
