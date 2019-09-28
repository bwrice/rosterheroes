<template>
    <v-btn color="success">
        <v-icon>unarchive</v-icon>
    </v-btn>
</template>

<script>
    import BarracksHero from "../../../../models/BarracksHero";

    export default {
        name: "FillSlotFromWagonButton",
        props: {
            hero: {
                type: BarracksHero,
            }
        }
    }
</script>

<style scoped>

</style>
