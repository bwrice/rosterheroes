<template>
    <PlayerSpiritSelection v-if="this._rosterFocusedHero" :hero="this._rosterFocusedHero"></PlayerSpiritSelection>
    <v-card key=2 v-else>
        <span class="display-3">{{this._squad.availableSpiritEssence}}</span>
        <div v-for="(hero, uuid) in this._squad.heroes">
            <HeroRosterCard :hero="hero"></HeroRosterCard>
        </div>
    </v-card>
</template>

<script>

    import HeroRosterCard from './HeroRosterCard';
    import PlayerSpiritSelection from './PlayerSpiritSelection';
    import { mapGetters } from 'vuex'

    export default {
        name: "SquadRoster",
        components: {
            HeroRosterCard,
            PlayerSpiritSelection
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_availableSpiritEssence',
                '_rosterFocusedHero'
            ])
        }
    }
</script>

<style scoped>

</style>