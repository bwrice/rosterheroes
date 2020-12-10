<template>
    <v-container>
        <v-row>
            <v-col cols="10" offset="1" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <CombatBattlefield></CombatBattlefield>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <v-row no-gutters align="center">
                    <v-col col="6">
                        <v-row no-gutters justify="center">
                            <span class="h3">
                                Moment: {{_sideQuestMoment}}
                            </span>
                        </v-row>
                        <v-row no-gutters justify="center">
                            <span class="h3">
                                Events: {{_triggeredSideQuestMessages.length}}
                            </span>
                        </v-row>
                        <v-row no-gutters justify="center">
                            <span class="h3">
                                Current Events: {{_currentSideQuestEvents.length}}
                            </span>
                        </v-row>
                    </v-col>
                    <v-col col="6">
                        <v-btn @click="toggle">
                            {{_sideQuestReplayPaused ? "Play" : "Pause"}}
                        </v-btn>
                        <v-btn @click="increaseBattlefieldSpeed">
                            Increase Speed
                        </v-btn>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-card>
                            <v-virtual-scroll
                                :items="eventMessages"
                                :height="384"
                                :item-height="64"
                            >
                                <template v-slot:default="{ item }">
                                    <CombatEventMessage :combat-event-message="item"></CombatEventMessage>
                                    <v-divider></v-divider>
                                </template>
                            </v-virtual-scroll>
                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import CombatBattlefield from "../../global/battlefield/CombatBattlefield";
    import CombatEventMessage from "../../campaign/CombatEventMessage";
    export default {
        name: "SideQuestReplayView",
        components: {CombatEventMessage, CombatBattlefield},
        data() {
            return {
                //
            }
        },
        watch: {
        },
        methods: {
            ...mapActions([
                'runSideQuestReplay',
                'pauseSideQuestReplay',
                'increaseBattlefieldSpeed'
            ]),
            toggle() {
                if (this._sideQuestReplayPaused) {
                    this.runSideQuestReplay();
                } else {
                    this.pauseSideQuestReplay();
                }
            },
        },
        computed: {
            ...mapGetters([
                '_sideQuestCombatSquad',
                '_sideQuestEnemyGroup',
                '_sideQuestMoment',
                '_triggeredSideQuestMessages',
                '_sideQuestReplayPaused',
                '_currentSideQuestEvents',
                '_sideQuestEventMessages'
            ]),
            battleFieldReady() {
                return this._sideQuestCombatSquad && this._sideQuestEnemyGroup
            },
            eventMessages() {
                return this._triggeredSideQuestMessages;
            }
        },
    }


</script>

<style scoped>

</style>
