<template>
    <v-card>
        <h3>{{hero.name}}</h3>
        <v-btn v-on:click="unFocus">Cancel</v-btn>
    </v-card>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        name: "PlayerSpiritSelection",
        props: ['hero'],

        methods: {
            ...mapActions([
                'setRosterFocusedHero'
            ]),
            unFocus: function() {
                this.setRosterFocusedHero(null);
            }
        }
    }
</script>

<style scoped>

</style>