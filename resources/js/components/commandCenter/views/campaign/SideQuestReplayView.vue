<template>
    <v-container>
        <v-row justify="center">
            <span class="text-body-2 text-sm-title text-lg-h6"
            >{{_sideQuestReplayDisabled ? 'Building Battlefield...' : _sideQuestResult.sideQuestSnapshot.name }}</span>
        </v-row>
        <v-row>
            <v-col cols="10" offset="1" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <v-row no-gutters class="px-3">
                    <v-col cols="12">
                        <CombatBattlefield></CombatBattlefield>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <v-card>
                    <v-toolbar flat>
                        <v-btn
                            fab
                            small
                            depressed
                            outlined
                            color="info"
                        >
                            {{ _sideQuestMoment }}
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            outlined
                            fab
                            small
                            color="primary"
                            @click="decreaseBattlefieldSpeed"
                            :disabled="_battlefieldSpeedBottomed || _sideQuestReplayDisabled"
                        >
                            <v-icon>remove</v-icon>
                        </v-btn>
                        <v-btn
                            outlined
                            fab
                            color="primary"
                            class="mx-1"
                            @click="toggle"
                            :disabled="_sideQuestReplayDisabled"
                        >
                            <v-icon large v-if="_sideQuestReplayPaused">play_arrow</v-icon>
                            <v-icon large v-else>pause</v-icon>
                        </v-btn>
                        <v-btn
                            outlined
                            fab
                            small
                            color="primary"
                            @click="increaseBattlefieldSpeed"
                            :disabled="_battlefieldSpeedMaxed || _sideQuestReplayDisabled"
                        >
                            <v-icon>add</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            outlined
                            fab
                            small
                            color="primary"
                            @click="rebuildSideQuestReplay"
                            :disabled="_sideQuestReplayDisabled"
                        >
                            <v-icon>refresh</v-icon>
                        </v-btn>
                    </v-toolbar>
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
        <v-dialog
            v-model="endDialog"
            max-width="460"
        >
            <v-card>
                <v-alert outlined :color="sideQuestVictory ? 'success' : 'error'" class="pa-0">
                    <v-card-title class="headline text-center">
                        {{sideQuestVictory ? 'Victory!' : 'Defeat'}}
                    </v-card-title>

                    <v-card-text>
                        {{endMessage}}
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-text class="blue-grey--text text--lighten-2">
                        <v-row no-gutters justify="space-between">
                            <span>Experience Earned:</span>
                            <span>{{experienceRewarded}}</span>
                        </v-row>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-text class="blue-grey--text text--lighten-2">
                        <v-row no-gutters justify="space-between">
                            <span>Favor Rewarded:</span>
                            <span>{{favorRewarded}}</span>
                        </v-row>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            @click="endDialog = false"
                        >
                            Close
                        </v-btn>
                    </v-card-actions>
                </v-alert>
            </v-card>
        </v-dialog>
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
                endDialog: false
            }
        },
        watch: {
            _sideQuestEndEvent(newValue) {
                this.endDialog = !!newValue;
            }
        },
        methods: {
            ...mapActions([
                'runSideQuestReplay',
                'pauseSideQuestReplay',
                'increaseBattlefieldSpeed',
                'decreaseBattlefieldSpeed',
                'rebuildSideQuestReplay'
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
                '_sideQuestResult',
                '_sideQuestCombatSquad',
                '_sideQuestEnemyGroup',
                '_sideQuestMoment',
                '_triggeredSideQuestMessages',
                '_sideQuestReplayPaused',
                '_currentSideQuestEvents',
                '_sideQuestEventMessages',
                '_battlefieldSpeedMaxed',
                '_battlefieldSpeedBottomed',
                '_sideQuestReplayDisabled',
                '_sideQuestEndEvent',
                '_squad'
            ]),
            battleFieldReady() {
                return this._sideQuestCombatSquad && this._sideQuestEnemyGroup
            },
            eventMessages() {
                return this._triggeredSideQuestMessages;
            },
            sideQuestVictory() {
                if (! this._sideQuestEndEvent) {
                    return false;
                }
                return this._sideQuestEndEvent.eventType === 'side-quest-victory';
            },
            endMessage() {
                let moments = this._sideQuestEndEvent ? this._sideQuestEndEvent.moment : '0';
                if (this.sideQuestVictory) {
                    return this._squad.name + ' is victorious against ' + this._sideQuestResult.sideQuestSnapshot.name
                        + ' in ' + moments + ' moments';
                } else {
                    return this._squad.name + ' was defeated by ' + this._sideQuestResult.sideQuestSnapshot.name
                        + ' in ' + moments + ' moments';
                }
            },
            experienceRewarded() {
                return this._sideQuestResult.experienceRewarded.toLocaleString();
            },
            favorRewarded() {
                return this._sideQuestResult.favorRewarded.toLocaleString();
            }
        },
    }


</script>

<style scoped>

</style>
