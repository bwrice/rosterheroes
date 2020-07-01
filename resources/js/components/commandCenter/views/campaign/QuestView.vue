<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <span class="title font-weight-thin">MAIN QUEST</span>
                    </v-col>
                </v-row>
                <v-sheet color="#466661" class="pa-2 rounded">
                    <v-row no-gutters justify="center">
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
                </v-sheet>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <v-row no-gutters>
                    <v-col cols="12">
                        <span class="title font-weight-thin">SIDE QUESTS</span>
                    </v-col>
                </v-row>
                <SideQuestPanel
                    v-for="(sideQuest, uuid) in quest.sideQuests"
                    :key="uuid"
                    :side-quest="sideQuest"
                >
                    <template v-slot:action>
                        <JoinSideQuestButton
                            :side-quest="sideQuest"
                            :quest="quest"
                        ></JoinSideQuestButton>
                    </template>
                </SideQuestPanel>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import SideQuestPanel from "../../campaign/SideQuestPanel";
    import JoinSideQuestButton from "../../campaign/JoinSideQuestButton";

    export default {
        name: "QuestView",
        components: {JoinSideQuestButton, SideQuestPanel},
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
