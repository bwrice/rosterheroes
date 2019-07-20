<template>
    <v-card>
        <v-card-title>
            <h3>{{hero.name}}</h3>
        </v-card-title>
        <div v-if="hero.playerSpirit">
            Filled
        </div>
        <div v-else>
            <v-btn v-on:click="focusHero">Edit</v-btn>
        </div>
    </v-card>
</template>

<script>

    import { mapActions } from 'vuex'

    export default {
        name: "HeroRosterCard",
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