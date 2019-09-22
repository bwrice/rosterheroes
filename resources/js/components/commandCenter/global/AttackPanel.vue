<template>
    <v-sheet color="rgba(69, 0, 92, .2)" class="px-2">
        <v-row>
            <v-col cols="7" class="py-1 px-2">
                <v-row class="no-gutters">
                    <v-col cols="3" class="pa-0">
                        <v-icon color="success">check_box</v-icon>
                    </v-col>
                    <v-col cols="9" class="text-center pa-0">
                        <span class="title">{{attack.name}}</span>
                    </v-col>
                </v-row>
                <v-row class="no-gutters">
                    <v-col cols="12" class="text-center">
                        <v-sheet color="rgba(0, 0, 0, .3)" class="py-2">
                            {{attack.baseDamage}}
                            <v-chip x-small label color="rgba(0, 0, 0, .3)" class="px-1" text-color="#FFFFFF">+</v-chip>
                            {{attack.damageMultiplier}}
                            <v-chip x-small label color="rgba(0, 0, 0, .3)" class="px-1" text-color="#FFFFFF">×</v-chip>
                            <v-chip label color="primary" class="px-2" text-color="#FFFFFF">FP</v-chip>
                        </v-sheet>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" class="py-1 caption">
                        Resource Costs:
                        <ul>
                            <li v-for="resourceCost in attack.resourceCosts">
                                {{resourceCost.description}}
                            </li>
                        </ul>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" class="py-1 caption">
                        Requirements: (none)
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="5" class="pa-1">
                <v-row>
                    <v-col cols="12" class="pa-0 text-center">
                        Speed: {{attack.combatSpeed}}
                    </v-col>
                </v-row>
                <v-row class="no-gutters">
                    <v-col cols="6" class="pa-0">
                        <v-sheet color="(255,255,255,0.3)" class="attack-icon">
                            <v-img
                                :src="attack.attackerPosition.icon.src"
                                :alt="attack.attackerPosition.icon.alt"
                            >
                            </v-img>
                        </v-sheet>
                    </v-col>
                    <v-col cols="6" class="pa-0">
                        <v-sheet color="(255,255,255,0.3)" class="attack-icon">
                            <v-img
                                :src="attack.targetPosition.icon.src"
                                :alt="attack.targetPosition.icon.alt"
                            >
                            </v-img>
                        </v-sheet>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="6"></v-col>
                    <v-col cols="6"></v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import Attack from "../../../models/Attack";

    export default {
        name: "AttackPanel",
        props: {
            attack: {
                type: Attack,
                required: true
            }
        }
    }
</script>

<style scoped>
    .attack-icon {
        margin: 2px;
    }
</style>
