<template>
    <v-btn color="success">
        <v-icon>unarchive</v-icon>
    </v-btn>
</template>

<script>
    import BarracksHero from "../../../../models/BarracksHero";
    import Slot from "../../../../models/Slot";
    import Item from "../../../../models/Item";

    export default {
        name: "FillSlotFromWagonButton",
        props: {
            hero: {
                type: BarracksHero,
                required: true
            },
            heroSlot: {
                type: Slot,
                required: true
            },
            item: {
                type: Item,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
