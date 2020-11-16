<template>
    <BaseAttackPanel :attack="attackSnapshot">
        <v-row no-gutters align="center">
            <v-col cols="4">
                <v-row no-gutters justify="center">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-progress-circular
                                :rotate="90"
                                :size="52"
                                :width="6"
                                :value="speedProgress"
                                color="accent"
                                v-bind="attrs"
                                v-on="on"
                            >
                            <span class="caption">
                                {{attackSnapshot.combatSpeed}}
                            </span>
                            </v-progress-circular>
                        </template>
                        <span>Speed</span>
                    </v-tooltip>
                </v-row>
            </v-col>
            <v-col cols="8">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-progress-linear
                            :value="damageProgress"
                            color="error"
                            height="25"
                            v-bind="attrs"
                            v-on="on"
                        >
                            {{attackSnapshot.damage}}
                        </v-progress-linear>
                    </template>
                    <span>Damage</span>
                </v-tooltip>
            </v-col>
        </v-row>
    </BaseAttackPanel>
</template>

<script>
    import AttackSnapshot from "../../../models/AttackSnapshot";
    import BaseAttackPanel from "../global/BaseAttackPanel";

    export default {
        name: "AttackSnapshotPanel",
        components: {BaseAttackPanel},
        props: {
            attackSnapshot: {
                type: AttackSnapshot,
                required: true
            }
        },
        computed: {
            speedProgress() {
                return 22 * Math.cbrt(this.attackSnapshot.combatSpeed);
            },
            damageProgress() {
                return Math.sqrt(this.attackSnapshot.damage);
            }
        }
    }
</script>

<style scoped>

</style>
