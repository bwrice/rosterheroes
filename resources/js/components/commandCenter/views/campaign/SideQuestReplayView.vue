<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <CombatBattlefield
                    :ally-health-percents="allyHealthPercents"
                    :enemy-health-percents="enemyHealthPercents"
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
                    </v-col>
                    <v-col col="6">
                        <v-btn @click="toggle">
                            Toggle
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
                }
            }
        },
        watch: {
            _sideQuestCombatSquad: function (newValue) {
                this.allyHealthPercents = combatGroupHealthPercents(newValue);
            },
            _sideQuestEnemyGroup: function (newValue) {
                this.enemyHealthPercents = combatGroupHealthPercents(newValue);
            }
        },
        methods: {
            ...mapActions([
                'runSideQuestReplay'
            ]),
        },
        computed: {
            ...mapGetters([
                '_sideQuestCombatSquad',
                '_sideQuestEnemyGroup',
                '_sideQuestMoment',
                '_triggeredSideQuestEvents'
            ]),
            battleFieldReady() {
                return this._sideQuestCombatSquad && this._sideQuestEnemyGroup
            },
        },
    }

    function combatGroupHealthPercents(combatGroup) {
        let frontLineInitialHealth = combatGroup.getHealthSum({combatPositionID: 1, healthProperty: 'initialHealth'});
        let frontLineCurrentHealth = combatGroup.getHealthSum({combatPositionID: 1, healthProperty: 'currentHealth'});
        let backLineInitialHealth = combatGroup.getHealthSum({combatPositionID: 2, healthProperty: 'initialHealth'});
        let backLineCurrentHealth = combatGroup.getHealthSum({combatPositionID: 2, healthProperty: 'currentHealth'});
        let highGroundInitialHealth = combatGroup.getHealthSum({combatPositionID: 3, healthProperty: 'initialHealth'});
        let highGroundCurrentHealth = combatGroup.getHealthSum({combatPositionID: 3, healthProperty: 'currentHealth'});
        return {
            frontLine: frontLineInitialHealth ? (frontLineCurrentHealth / frontLineInitialHealth) * 100 : 0,
            backLine: backLineInitialHealth ? (backLineCurrentHealth / backLineInitialHealth) * 100 : 0,
            highGround: highGroundInitialHealth ? (highGroundCurrentHealth / highGroundInitialHealth) * 100 : 0
        }
    }
</script>

<style scoped>

</style>
