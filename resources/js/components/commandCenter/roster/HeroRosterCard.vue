<template>
    <v-card>
        <v-card-title>
            <h3>{{hero.name}}</h3>
        </v-card-title>
        <div v-if="hero.playerSpirit">
            <PlayerSpiritPanel :player-spirit="hero.playerSpiritObject">
                <template v-slot:spirit-actions>
                    <v-btn v-on:click="focusHero">Edit</v-btn>
                    <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpiritObject"></RemoveSpiritButton>
                </template>
            </PlayerSpiritPanel>
        </div>
        <div v-else>
            <v-btn v-on:click="focusHero">Edit</v-btn>
        </div>
    </v-card>
</template>

<script>

    import { mapActions } from 'vuex'
    import PlayerSpiritPanel from "./PlayerSpiritPanel";
    import RemoveSpiritButton from "./RemoveSpiritButton";

    export default {
        name: "HeroRosterCard",
        components: {RemoveSpiritButton, PlayerSpiritPanel},
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