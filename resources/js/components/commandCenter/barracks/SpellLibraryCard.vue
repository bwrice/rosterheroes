<template>
    <v-card>
        <v-card-title>Spell Library</v-card-title>
        <v-card-text>
            <SpellPanelIterator :spells="spells"></SpellPanelIterator>
        </v-card-text>
    </v-card>
</template>

<script>
    import SpellPanelIterator from "./SpellPanelIterator";
    export default {
        name: "SpellLibraryCard",
        components: {SpellPanelIterator},
        props: {
            spells: {
                type: Array,
                required: true
            }
        },
    }
</script>

<style scoped>

</style>
