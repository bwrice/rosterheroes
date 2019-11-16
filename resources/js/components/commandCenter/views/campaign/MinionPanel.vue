<template>
    <v-sheet color="#3a474a" class="pa-1">
        <v-row no-gutters align="center">
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
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-row no-gutters>
                            </v-row>
                        </v-col>
                    </v-row>
                    <div style="width: 70px">
                        <CombatPositionIcon :combat-position-id="minion.combatPositionID" :attacker-mode="false"></CombatPositionIcon>
                    </div>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
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
                return this.minion.name + ' (x' + this.minion.count + ')';
            }
        }
    }
</script>

<style scoped>

</style>
