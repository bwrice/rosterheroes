<template>
    <v-sheet color="#564c59">
        <span class="subtitle-2">{{spell.name}}</span>
    </v-sheet>
</template>

<script>
    import Spell from "../../../models/Spell";

    export default {
        name: "SpellPanel",
        props: {
            spell: {
                type: Spell,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
