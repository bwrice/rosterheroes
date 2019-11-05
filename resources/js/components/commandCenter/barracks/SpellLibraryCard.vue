<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">SPELL LIBRARY</span>
        </v-col>
        <v-col cols="12">
            <SpellPanelIterator :spells="spells"></SpellPanelIterator>
        </v-col>
    </v-row>
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
