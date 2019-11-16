<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-row no-gutters justify="center">
                <span class="title font-weight-light">
                    {{title}}
                </span>
            </v-row>
            <v-row no-gutters justify="space-between" align="center">
                <v-chip
                    label
                >
                    {{minion.level}}
                </v-chip>
                <div style="width: 70px">
                    <CombatPositionIcon :combat-position-id="minion.combatPositionID" :attacker-mode="false"></CombatPositionIcon>
                </div>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import Minion from "../../../../models/Minion";
    import CombatPositionIcon from "../../global/CombatPositionIcon";

    export default {
        name: "MinionPanel",
        components: {CombatPositionIcon},
        props: {
            minion: {
                type: Minion,
                required: true
            }
        },
        computed: {
            title() {
                return this.minion.name + ' x' + this.minion.count + '';
            }
        }
    }
</script>

<style scoped>

</style>
