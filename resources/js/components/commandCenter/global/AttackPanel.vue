<template>
    <v-sheet color="rgba(60, 46, 92, .75)" class="mb-1 rounded">
        <v-row align="center" justify="center" class="mx-2" no-gutters>
            <span class="subtitle-2 pa-2">{{attack.name}}</span>
            <div class="flex-grow-1"></div>
            <v-btn @click="expanded = ! expanded"
                   fab
                   dark
                   x-small
                   color="rgba(0, 0, 0, .4)"
            >
                <v-icon v-if="expanded">expand_less</v-icon>
                <v-icon v-else>expand_more</v-icon>
            </v-btn>
        </v-row>
        <v-row v-if="expanded" no-gutters class="px-2">
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
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-chip label color="primary" class="px-2" text-color="#FFFFFF" v-on="on">FP</v-chip>
                                </template>
                                <span>Fantasy Power</span>
                            </v-tooltip>
                        </v-sheet>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12" class="py-1 px-3 caption">
                        {{resourceCostsTitle}}
                        <ul>
                            <li v-for="resourceCost in attack.resourceCosts">
                                {{resourceCost.description}}
                            </li>
                        </ul>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12" class="py-1 px-3 caption">
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
                    <v-col cols="6">
                        <CombatPositionIcon :attacker-mode="true" :combat-position-id="attack.attackerPositionID"></CombatPositionIcon>
                    </v-col>
                    <v-col cols="6">
                        <CombatPositionIcon :attacker-mode="false" :combat-position-id="attack.targetPositionID"></CombatPositionIcon>
                    </v-col>
                    <v-col cols="6">
                        <DamageTypeIcon :damage-type-id="attack.damageTypeID" :targets-count="attack.targetsCount"></DamageTypeIcon>
                    </v-col>
                    <v-col cols="6">
                        <TargetPriorityIcon :target-priority-id="attack.targetPriorityID"></TargetPriorityIcon>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import Attack from "../../../models/Attack";
    import SvgIconSheet from "./SvgIconSheet";
    import CombatPositionIcon from "../../icons/CombatPositionIcon";
    import DamageTypeIcon from "../../icons/damageTypes/DamageTypeIcon";
    import TargetPriorityIcon from "../../icons/targetPriorities/TargetPriorityIcon";

    export default {
        name: "AttackPanel",
        components: {TargetPriorityIcon, DamageTypeIcon, CombatPositionIcon, SvgIconSheet},
        props: {
            attack: {
                type: Attack,
                required: true
            }
        },
        data() {
            return {
                expanded: false
            }
        },
        computed: {
            resourceCostsTitle() {
                if (this.attack.resourceCosts.length > 0) {
                    return 'Resource Costs:';
                }
                return 'Resource Costs: (none)'
            }
        }
    }
</script>

<style scoped>

</style>
