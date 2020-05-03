<template>
    <v-container>
        <v-row>
            <v-col cols="12" sm="10" offset-sm="1" xl="8" offset-xl="2">
                <v-row>
                    <v-col cols="12">
                        <v-row no-gutters justify="center" align="center">
                            <span class="display-1 rh-op-85 font-weight-bold mx-3 text-center">{{quest.name}}</span>
                        </v-row>
                        <v-row no-gutters align="center" class="mt-2">
                            <v-col cols="12" md="6" offset-md="3" lg="4" offset-lg="4">
                                <v-btn v-if="joined"
                                       block
                                       color="error"
                                       :disabled="! canLeave"
                                       @click="leave"
                                >
                                    Leave Quest
                                </v-btn>
                                <v-btn v-else
                                       color="primary"
                                       block
                                       :disabled="! canJoin"
                                       @click="join"
                                >
                                    Join Quest
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col v-for="(sideQuest, uuid) in quest.sideQuests" :key="uuid" cols="12" md="6" xl="4">
                        <SideQuestCard :side-quest="sideQuest" :quest="quest"></SideQuestCard>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import SideQuestCard from "../../campaign/SideQuestCard";

    export default {
        name: "QuestView",
        components: {SideQuestCard},
        data() {
            return {
                pending: false
            }
        },
        methods: {
            ...mapActions([
                'joinQuest',
                'leaveQuest'
            ]),
            async join() {
                this.pending = true;
                await this.joinQuest({
                    quest: this.quest
                });
                this.pending = false;
            },
            async leave() {
                this.pending = true;
                await this.leaveQuest({
                    questUuid: this.quest.uuid,
                    questName: this.quest.name
                });
                this.pending = false;
            }
        },
        computed: {
            ...mapGetters([
                '_currentLocationQuestBySlug',
                '_currentCampaign',
                '_matchingCampaignStop',
                '_squad'
            ]),
            quest() {
                let slug = this.$route.params.questSlug;
                return this._currentLocationQuestBySlug(slug);
            },
            joined() {
                return this._matchingCampaignStop(this.quest.uuid);
            },
            canJoin() {
                if (this.pending) {
                    return false;
                }
                if (! this._currentCampaign) {
                    return true;
                }
                if (this.joined) {
                    return false;
                }
                return this._currentCampaign.campaignStops.length < this._squad.questsPerWeek;
            },
            canLeave() {
                return this.joined && (! this.pending);
            }
        }
    }
</script>

<style scoped>

</style>
