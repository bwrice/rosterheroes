<template>
    <v-btn v-on:click="focusHero" class="primary">Edit</v-btn>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        name: "EditSpiritButton",
        props: ['hero'],
        methods: {
            ...mapActions([
                'setRosterFocusedHero'
            ]),
            focusHero: function() {
                this.setRosterFocusedHero(this.hero);
            }
        }
    }
</script>

<style scoped>

</style>