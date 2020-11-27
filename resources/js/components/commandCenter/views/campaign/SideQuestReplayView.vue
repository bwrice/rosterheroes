<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <CombatBattlefield
                    :ally-health-percents="allyHealthPercents"
                    :enemy-health-percents="enemyHealthPercents"
                    :ally-damages="allyDamages"
                    :enemy-damages="enemyDamages"
                    :ally-blocks="allyBlocks"
                ></CombatBattlefield>
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
                                Events: {{_triggeredSideQuestEvents.length}}
                            </span>
                        </v-row>
                        <v-row no-gutters justify="center">
                            <span class="h3">
                                Current Events: {{_currentSideQuestEvents.length}}
                            </span>
                        </v-row>
                        <v-row no-gutters justify="center">
                            <span class="h3">
                                DHFEs: {{allyDamages.length}}
                            </span>
                        </v-row>
                    </v-col>
                    <v-col col="6">
                        <v-btn @click="toggle">
                            {{_sideQuestReplayPaused ? "Play" : "Pause"}}
                        </v-btn>
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
    export default {
        name: "SideQuestReplayView",
        components: {CombatBattlefield},
        data() {
            return {
                allyHealthPercents: {
                    frontLine: 0,
                    backLine: 0,
                    highGround: 0
                },
                enemyHealthPercents: {
                    frontLine: 0,
                    backLine: 0,
                    highGround: 0
                },
                allyDamages: {
                    frontLine: [],
                    backLine: [],
                    highGround: [],
                },
                enemyDamages: {
                    frontLine: [],
                    backLine: [],
                    highGround: [],
                },
                allyBlocks: {
                    frontLine: [],
                    backLine: [],
                    highGround: []
                }
            }
        },
        watch: {
            _sideQuestCombatSquad: function (newValue) {
                this.allyHealthPercents = this.combatGroupHealthPercents(newValue);
            },
            _sideQuestEnemyGroup: function (newValue) {
                this.enemyHealthPercents = this.combatGroupHealthPercents(newValue);
            },
            _currentSideQuestEvents: function (newEvents) {
                this.allyDamages = this.convertEventsToAllyDamages(newEvents);
                this.enemyDamages = this.convertEventsToEnemyDamages(newEvents);
                this.allyBlocks = this.convertEventsToAllyBlocks(newEvents);
            }
        },
        methods: {
            ...mapActions([
                'runSideQuestReplay',
                'pauseSideQuestReplay'
            ]),
            toggle() {
                if (this._sideQuestReplayPaused) {
                    this.runSideQuestReplay();
                } else {
                    this.pauseSideQuestReplay();
                }
            },
            combatGroupHealthPercents(combatGroup) {
                let frontLineInitialHealth = combatGroup.getHealthSum({
                    combatPositionID: 1,
                    healthProperty: 'initialHealth'
                });
                let frontLineCurrentHealth = combatGroup.getHealthSum({
                    combatPositionID: 1,
                    healthProperty: 'currentHealth'
                });
                let backLineInitialHealth = combatGroup.getHealthSum({
                    combatPositionID: 2,
                    healthProperty: 'initialHealth'
                });
                let backLineCurrentHealth = combatGroup.getHealthSum({
                    combatPositionID: 2,
                    healthProperty: 'currentHealth'
                });
                let highGroundInitialHealth = combatGroup.getHealthSum({
                    combatPositionID: 3,
                    healthProperty: 'initialHealth'
                });
                let highGroundCurrentHealth = combatGroup.getHealthSum({
                    combatPositionID: 3,
                    healthProperty: 'currentHealth'
                });
                return {
                    frontLine: frontLineInitialHealth ? (frontLineCurrentHealth / frontLineInitialHealth) * 100 : 0,
                    backLine: backLineInitialHealth ? (backLineCurrentHealth / backLineInitialHealth) * 100 : 0,
                    highGround: highGroundInitialHealth ? (highGroundCurrentHealth / highGroundInitialHealth) * 100 : 0
                }
            },

            convertEventsToAllyDamages(sqEvents) {
                let damageEvents = sqEvents.filter(sqEvent => sqEvent.eventType === 'minion-damages-hero');

                return {
                    frontLine: this.convertToDamagesByCombatPosition(damageEvents, 1, this._sideQuestCombatSquad, 'hero'),
                    backLine: this.convertToDamagesByCombatPosition(damageEvents, 2, this._sideQuestCombatSquad, 'hero'),
                    highGround: this.convertToDamagesByCombatPosition(damageEvents, 3, this._sideQuestCombatSquad, 'hero'),
                }
            },

            convertEventsToEnemyDamages(sqEvents) {
                let damageEvents = sqEvents.filter(sqEvent => sqEvent.eventType === 'hero-damages-minion');

                return {
                    frontLine: this.convertToDamagesByCombatPosition(damageEvents, 1, this._sideQuestEnemyGroup, 'minion'),
                    backLine: this.convertToDamagesByCombatPosition(damageEvents, 2, this._sideQuestEnemyGroup, 'minion'),
                    highGround: this.convertToDamagesByCombatPosition(damageEvents, 3, this._sideQuestEnemyGroup, 'minion'),
                }
            },

            convertEventsToAllyBlocks(sqEvents) {
                let blockEvents = sqEvents.filter(sqEvent => sqEvent.eventType === 'hero-blocks-minion');

                // We'll map into array of empty objects so any watchers pick up changes
                return {
                    frontLine: this.filterEventsByCombatPosition(blockEvents, 1, this._sideQuestCombatSquad, 'hero').map(event => new Object({})),
                    backLine: this.filterEventsByCombatPosition(blockEvents, 2, this._sideQuestCombatSquad, 'hero').map(event => new Object({})),
                    highGround: this.filterEventsByCombatPosition(blockEvents, 3, this._sideQuestCombatSquad, 'hero').map(event => new Object({})),
                }
            },


            convertToDamagesByCombatPosition(sqEvents, combatPositionID, combatGroup, combatantKey) {
                return this.filterEventsByCombatPosition(sqEvents, combatPositionID, combatGroup, combatantKey).map(sqEvent => sqEvent.data.damage);
            },

            filterEventsByCombatPosition(sqEvents, combatPositionID, combatGroup, combatantKey) {
                return sqEvents.filter(function (sqEvent) {
                    let matchingCombatant = combatGroup.combatants.find(combatant => combatant.combatantUuid === sqEvent.data[combatantKey].combatantUuid);
                    if (matchingCombatant) {
                        return matchingCombatant.combatPositionID === combatPositionID;
                    }
                    return false;
                });
            }
        },
        computed: {
            ...mapGetters([
                '_sideQuestCombatSquad',
                '_sideQuestEnemyGroup',
                '_sideQuestMoment',
                '_triggeredSideQuestEvents',
                '_sideQuestReplayPaused',
                '_currentSideQuestEvents'
            ]),
            battleFieldReady() {
                return this._sideQuestCombatSquad && this._sideQuestEnemyGroup
            },
        },
    }


</script>

<style scoped>

</style>
