<template>
    <BaseAttackPanel :attack="attack">
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
    </BaseAttackPanel>
</template>

<script>
    import Attack from "../../../models/Attack";
    import BaseAttackPanel from "./BaseAttackPanel";

    export default {
        name: "AttackPanel",
        components: {BaseAttackPanel},
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
