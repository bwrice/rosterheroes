<template>
    <v-sheet>
        <v-row>
            <v-col cols="2"></v-col>
            <v-col cols="6">{{attack.name}}</v-col>
            <v-col cols="4">
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

</style>
