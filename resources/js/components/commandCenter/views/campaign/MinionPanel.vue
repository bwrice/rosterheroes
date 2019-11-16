<template>
    <v-sheet color="#3a474a" class="pa-1">
        <v-row no-gutters justify="center" align="center">
            <v-chip
                label
                color="accent darken-2"
            >
                {{minion.level}}
            </v-chip>
            <span class="title font-weight-light mx-2">
                {{title}}
            </span>
        </v-row>
        <v-row no-gutters>
            <v-col cols="12">
                <v-row no-gutters justify="space-between" align="center">
                    <v-row no-gutters class="flex-column">
                        <span class="subtitle-2 rh-op-75">HEALTH: {{minion.startingHealth}}</span>
                        <span class="subtitle-2 rh-op-75">PROTECTION: {{minion.protection}}</span>
                        <span class="subtitle-2 rh-op-75">BLOCK: {{minion.blockChance}}%</span>
                    </v-row>
                    <v-row no-gutters justify="end">
                        <div style="width: 70px">
                            <CombatPositionIcon :combat-position-id="minion.combatPositionID" :attacker-mode="false"></CombatPositionIcon>
                        </div>
                    </v-row>
                </v-row>
                <v-row class="no-gutters">
                    <v-col cols="12" class="px-1">
                        <span class="subtitle-2 rh-op-75">ATTACKS:</span>
                        <AttackPanel v-for="attack in minion.attacks" v-bind:key="attack.name" :attack="attack"></AttackPanel>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import Minion from "../../../../models/Minion";
    import CombatPositionIcon from "../../global/CombatPositionIcon";
    import AttackPanel from "../../global/AttackPanel";

    export default {
        name: "MinionPanel",
        components: {AttackPanel, CombatPositionIcon},
        props: {
            minion: {
                type: Minion,
                required: true
            }
        },
        computed: {
            title() {
                return this.minion.name + ' (x' + this.minion.count + ')';
            }
        }
    }
</script>

<style scoped>

</style>
