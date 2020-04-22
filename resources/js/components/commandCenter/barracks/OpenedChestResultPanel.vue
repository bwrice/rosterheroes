<template>
    <v-row no-gutters>
        <v-col
            cols="12"
            v-for="(item, uuid) in openedChestResult.items"
            :key="uuid"
        >
            <ItemExpandPanel :item="item"></ItemExpandPanel>
        </v-col>
    </v-row>
</template>

<script>
    import OpenedChestResult from "../../../models/OpenedChestResult";
    import ItemExpandPanel from "../global/ItemExpandPanel";

    export default {
        name: "OpenedChestResultPanel",
        components: {ItemExpandPanel},
        props: {
            openedChestResult: {
                type: OpenedChestResult,
                required: true
            }
        },
    }
</script>

<style scoped>

</style>
