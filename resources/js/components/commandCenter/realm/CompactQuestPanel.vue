<template>
    <v-col cols="12">
        <v-sheet :tile="true" :color="'#c99128'">
            <v-row no-gutters justify="space-between" align="center" class="pa-1">
                <v-chip :color="'#3a474a'">{{compactQuest.level}}</v-chip>
                <span class="mx-3 title font-weight-regular">{{compactQuest.name}}</span>
            </v-row>
        </v-sheet>
    </v-col>
</template>

<script>
    import CompactQuest from "../../../models/compact/CompactQuest";

    export default {
        name: "CompactQuestPanel",
        props: {
            compactQuest: {
                type: CompactQuest,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
