<template>
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
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import SideQuest from "../../../models/SideQuest";
    import Quest from "../../../models/Quest";

    export default {
        name: "JoinSideQuestButton",
        props: {
            sideQuest: {
                type: SideQuest,
                required: true
            },
            quest: {
                type: Quest,
                required: true
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
