<template>
    <v-card>
        <v-btn v-on:click="unFocus">
            <v-icon dark left>arrow_back</v-icon>Back
        </v-btn>
        <HeroRosterCard :hero="hero">
            <template slot="body">
                <template v-if="hero.playerSpirit">
                    <PlayerSpiritPanel :player-spirit="getFocusedPlayerSpirit(hero.playerSpirit)">
                        <template v-slot:spirit-actions>
                            <RemoveSpiritButton :hero="hero" :player-spirit="getFocusedPlayerSpirit(hero.playerSpirit)"></RemoveSpiritButton>
                        </template>
                    </PlayerSpiritPanel>
                </template>
            </template>
        </HeroRosterCard>
        <v-data-iterator
                :items="this.playerSpirits"
                content-tag="v-layout"
                hide-actions
                row
                wrap
        >
            <template v-slot:header>
                <v-toolbar
                        class="mb-2"
                        color="secondary"
                        dark
                        flat
                >
                    <v-toolbar-title>Select Player Spirit</v-toolbar-title>
                </v-toolbar>
            </template>
            <template v-slot:item="props">
                <v-flex
                        fluid
                        py-1
                        xs12
                >
                    <PlayerSpiritPanel :player-spirit="props.item">
                        <template v-slot:spirit-actions>
                            <AddSpiritButton :hero="hero" :player-spirit="props.item"></AddSpiritButton>
                        </template>
                    </PlayerSpiritPanel>
                </v-flex>
            </template>
        </v-data-iterator>
    </v-card>
</template>

<script>
    import { mapActions } from 'vuex';
    import { mapGetters } from 'vuex';

    import PlayerSpiritPanel from '../../roster/PlayerSpiritPanel';
    import AddSpiritButton from "../../roster/AddSpiritButton";
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import HeroRosterCard from "../../roster/HeroRosterCard";

    import PlayerSpirit from "../../../../models/PlayerSpirit";
    import Squad from "../../../../models/Squad";
    import Hero from "../../../../models/Hero";
    import Week from "../../../../models/Week";

    export default {
        name: "HeroRosterView",

        components: {
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

        async mounted() {
            this.hero = await Hero.$find(this.$route.params.heroSlug);
            // this.playerSpirits = await this._currentWeek.playerSpirits().where('hero-race', this.hero.heroRace.name).$get();
            // this.updatePlayerSpiritsPool();
        },

        data: function() {
            return {
                hero: {
                    heroRace: {}
                },
                playerSpirits: []
            }
        },

        watch: {
            _currentWeek: function(newWeek) {
                this.setPlayerSpirits(newWeek);
            }
        },

        methods: {
            ...mapActions([
                'setRosterFocusedHero'
            ]),
            setHero: function() {
                let squad = new Squad(this._squad);
                console.log("Heroes");
                console.log(squad.heroes);
                this.hero = squad.getHero(this.$route.params.heroSlug);
            },
            setPlayerSpirits: async function(currentWeek) {
                if (currentWeek && currentWeek.uuid) {
                    console.log("CURRENT WEEK");
                    console.log(currentWeek);
                    let week = new Week(currentWeek);
                    this.playerSpirits = await week.playerSpirits().where('hero-race', this.hero.heroRace.name).$get();
                }
            },
            unFocus: function() {
                this.setRosterFocusedHero(null);
            },
            getFocusedPlayerSpirit: function(playerSpirit) {
                return new PlayerSpirit(playerSpirit);
            }
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_currentWeek'
            ]),
            // playerSpirits: function() {
            //     if (this._currentWeek) {
            //         return [];
            //         // let week = new Week(this._currentWeek.uuid);
            //         // return await week.playerSpirits().where('hero-race', this.hero.heroRace.name).$get();
            //     }
            //     return [];
            // }
        },
    }
</script>

<style scoped>

</style>