<template>
    <v-card>
        <span class="display-3">{{this._availableSpiritEssence}}</span>
        <div v-for="(hero, uuid) in this._squad.heroes">
            <HeroRosterCard :hero="hero"></HeroRosterCard>
        </div>
    </v-card>
</template>

<script>

    import HeroRosterCard from './HeroRosterCard';
    import { mapGetters } from 'vuex'

    export default {
        name: "SquadRoster",
        components: {
            HeroRosterCard
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