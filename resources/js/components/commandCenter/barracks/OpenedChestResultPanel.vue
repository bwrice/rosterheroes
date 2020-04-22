<template>
    <v-row no-gutters>
        <v-col
            cols="12"
            v-for="(item, uuid) in openedChestResult.items"
            :key="uuid"
        >
            <ItemCard :item="item"></ItemCard>
        </v-col>
    </v-row>
</template>

<script>
    import OpenedChestResult from "../../../models/OpenedChestResult";
    import ItemCard from "../global/ItemCard";

    export default {
        name: "OpenedChestResultPanel",
        components: {ItemCard},
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
