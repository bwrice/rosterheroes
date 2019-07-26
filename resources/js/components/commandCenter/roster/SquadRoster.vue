<template>
    <HeroSpiritSelection v-if="this._rosterFocusedHero" :hero="this._rosterFocusedHero"></HeroSpiritSelection>
    <v-card v-else>
        <span class="display-3">{{this._squad.availableSpiritEssence}}</span>
        <div v-for="(hero, uuid) in this.heroes">
            <HeroRosterCard :hero="hero">
                <template slot="body">
                    <div v-if="hero.playerSpirit">
                        <PlayerSpiritPanel :player-spirit="getPlayerSpirit(hero.playerSpirit)">
                            <template v-slot:spirit-actions>
                                <EditSpiritButton :hero="hero"></EditSpiritButton>
                                <RemoveSpiritButton :hero="hero" :player-spirit="getPlayerSpirit(hero.playerSpirit)"></RemoveSpiritButton>
                            </template>
                        </PlayerSpiritPanel>
                    </div>
                    <div v-else>
                        <EditSpiritButton :hero="hero"></EditSpiritButton>
                    </div>
                </template>
            </HeroRosterCard>
        </div>
    </v-card>
</template>

<script>

    import HeroRosterCard from './HeroRosterCard';
    import HeroSpiritSelection from './HeroSpiritSelection';
    import RemoveSpiritButton from "./RemoveSpiritButton";
    import EditSpiritButton from "./EditSpiritButton";
    import PlayerSpiritPanel from "./PlayerSpiritPanel";

    import PlayerSpirit from "../../../models/PlayerSpirit";

    import { mapGetters } from 'vuex'

    export default {
        name: "SquadRoster",
        components: {
            HeroRosterCard,
            HeroSpiritSelection,
            EditSpiritButton,
            RemoveSpiritButton,
            PlayerSpiritPanel
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
        },
        methods: {
            getPlayerSpirit: function(playerSpirit) {
                return new PlayerSpirit(playerSpirit)
            }
        }
    }
</script>

<style scoped>

</style>