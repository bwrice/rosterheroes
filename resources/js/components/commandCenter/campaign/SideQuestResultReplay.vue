<template>
    <v-sheet
    >
        <v-row no-gutters justify="center">
            <span class="title rh-op-85">{{sideQuestResult.sideQuest.name}}</span>
        </v-row>
    </v-sheet>
</template>

<script>
    import SideQuestResult from "../../../models/SideQuestResult";

    export default {
        name: "SideQuestResultReplay",
        props: {
            sideQuestResult: {
                type: SideQuestResult,
                required: true
            },
            sideQuestEvents: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
