<template>
    <HeroSpiritSelection v-if="this._rosterFocusedHero" :hero="this._rosterFocusedHero"></HeroSpiritSelection>
    <v-card v-else>
        <span class="display-3">{{this._squad.availableSpiritEssence}}</span>
        <div v-for="(hero, uuid) in this.heroes">
            <HeroRosterCard :hero="hero"></HeroRosterCard>
        </div>
    </v-card>
</template>

<script>

    import HeroRosterCard from './HeroRosterCard';
    import HeroSpiritSelection from './HeroSpiritSelection';
    import { mapGetters } from 'vuex'

    export default {
        name: "SquadRoster",
        components: {
            HeroRosterCard,
            HeroSpiritSelection
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_availableSpiritEssence',
                '_rosterFocusedHero'
            ]),
            heroes: function() {
                let _heroes = [];
                if (this._squad.heroPosts) {
                    this._squad.heroPosts.forEach(function (heroPost) {
                        if (heroPost.hero) {
                            _heroes.push(heroPost.hero);
                        }
                    });
                }
                return _heroes;
            }
        }
    }
</script>

<style scoped>

</style>