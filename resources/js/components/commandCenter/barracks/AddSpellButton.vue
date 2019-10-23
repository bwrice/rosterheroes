<template>
    <v-btn class="my-2" block color="primary">Cast Spell</v-btn>
</template>

<script>
    import Spell from "../../../models/Spell";
    import Hero from "../../../models/Hero";

    export default {
        name: "AddSpellButton",
        props: {
            hero: {
                type: Hero,
                required: true
            },
            spell: {
                type: Spell,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
