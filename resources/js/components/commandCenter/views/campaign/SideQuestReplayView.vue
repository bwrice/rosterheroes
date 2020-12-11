<template>
    <v-container>
        <v-row>
            <v-col cols="10" offset="1" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <CombatBattlefield></CombatBattlefield>
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
                            :disabled="_battlefieldSpeedBottomed"
                        >
                            <v-icon>remove</v-icon>
                        </v-btn>
                        <v-btn outlined fab color="primary" class="mx-1" @click="toggle">
                            <v-icon large v-if="_sideQuestReplayPaused">play_arrow</v-icon>
                            <v-icon large v-else>pause</v-icon>
                        </v-btn>
                        <v-btn
                            outlined
                            fab
                            small
                            color="primary"
                            @click="increaseBattlefieldSpeed"
                            :disabled="_battlefieldSpeedMaxed"
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
                '_sideQuestCombatSquad',
                '_sideQuestEnemyGroup',
                '_sideQuestMoment',
                '_triggeredSideQuestMessages',
                '_sideQuestReplayPaused',
                '_currentSideQuestEvents',
                '_sideQuestEventMessages',
                '_battlefieldSpeedMaxed',
                '_battlefieldSpeedBottomed',
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
