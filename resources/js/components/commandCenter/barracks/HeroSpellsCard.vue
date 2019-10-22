<template>
    <v-card>
        <v-card-title>Spells</v-card-title>
    </v-card>
</template>

<script>
    import Hero from "../../../models/Hero";

    export default {
        name: "HeroSpellsCard",
        props: {
            hero: {
                type: Hero,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
