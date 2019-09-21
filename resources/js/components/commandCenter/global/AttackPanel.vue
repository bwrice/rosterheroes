<template>
    <v-row>
        <v-col cols="8">
            <v-row>
                <v-col cols="4">
                    ✔
                </v-col>
                <v-col cols="8">
                    {{attack.name}}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    {{attack.baseDamage}}
                    <v-chip x-small label color="secondary" class="px-1" text-color="#FFFFFF">+</v-chip>
                    {{attack.damageMultiplier}}
                    <v-chip x-small label color="secondary" class="px-1" text-color="#FFFFFF">×</v-chip>
                    <v-chip label color="primary" class="px-2" text-color="#FFFFFF">FP</v-chip>
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
        <v-col cols="4">
            <v-row>
                <v-col cols="12">
                    Speed: {{attack.combatSpeed}}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="6" class="pa-0">
                    <v-img
                        :src="attack.attackerPosition.icon.src"
                        :alt="attack.attackerPosition.icon.alt"
                    >
                    </v-img>
                </v-col>
                <v-col cols="6" class="pa-0">
                    <v-img
                        :src="attack.targetPosition.icon.src"
                        :alt="attack.targetPosition.icon.alt"
                    >
                    </v-img>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="6"></v-col>
                <v-col cols="6"></v-col>
            </v-row>
        </v-col>
    </v-row>
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

</style>
