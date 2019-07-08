<template>
    <v-card>
        <span class="display-3">{{this._availableSpiritEssence}}</span>
        <div v-for="(hero, uuid) in this._squad.heroes">
            <FilledHeroRosterCard v-if="hero.weeklyGamePlayer" :hero="hero"></FilledHeroRosterCard>
            <EmptyHeroRosterCard v-else :hero="hero"></EmptyHeroRosterCard>
        </div>
    </v-card>
</template>

<script>

    import EmptyHeroRosterCard from './EmptyHeroRosterCard';
    import FilledHeroRosterCard from './FilledHeroRosterCard';
    import { mapGetters } from 'vuex'

    export default {
        name: "SquadRoster",
        components: {
            EmptyHeroRosterCard,
            FilledHeroRosterCard
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_availableSpiritEssence'
            ])
        },
    }
</script>

<style scoped>

</style>