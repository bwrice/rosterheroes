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
        <div v-if="expanded" class="px-2">
            <v-row no-gutters justify="center">
                <span class="title">{{attack.name}}</span>
            </v-row>
            <v-row no-gutters>
                <v-col cols="7" class="py-1 px-1">
                    <slot>
                        <!-- Default Slot -->
                    </slot>
                    <v-row no-gutters>
                        <v-col cols="12" class="py-1 px-1 caption">
                            {{resourceCostsTitle}}
                            <ul>
                                <li v-for="resourceCost in attack.resourceCosts">
                                    {{resourceCost.description}}
                                </li>
                            </ul>
                        </v-col>
                    </v-row>
                    <v-row no-gutters>
                        <v-col cols="12" class="py-1 px-1 caption">
                            Requirements: (none)
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="5" class="px-1">
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
        </div>
    </v-sheet>
</template>

<script>
    import SvgIconSheet from "./SvgIconSheet";
    import CombatPositionIcon from "../../icons/CombatPositionIcon";
    import DamageTypeIcon from "../../icons/damageTypes/DamageTypeIcon";
    import TargetPriorityIcon from "../../icons/targetPriorities/TargetPriorityIcon";
    import BaseAttack from "../../../models/BaseAttack";

    export default {
        name: "BaseAttackPanel",
        components: {TargetPriorityIcon, DamageTypeIcon, CombatPositionIcon, SvgIconSheet},
        props: {
            attack: {
                type: BaseAttack,
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
