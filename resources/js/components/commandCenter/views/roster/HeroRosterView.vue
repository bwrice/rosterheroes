<template>
    <v-card>
        <v-btn v-on:click="unFocus">
            <v-icon dark left>arrow_back</v-icon>Back
        </v-btn>
        <HeroRosterCard :hero="hero" v-if="hero">
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
    import Week from "../../../../models/Week";

    export default {
        name: "HeroRosterView",

        components: {
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

        data: function() {
            return {
                hero: null,
                playerSpirits: [],
                week: null
            }
        },

        watch: {
            _currentWeek: function(week) {
                this.week = week;
                if (this.week && this.hero) {
                    this.setPlayerSpirits();
                }
            },
            _hero: function(hero) {
                this.hero = hero;
                if (this.week && this.hero) {
                    this.setPlayerSpirits();
                }
            }
        },

        methods: {
            ...mapActions([
                'setRosterFocusedHero'
            ]),
            setPlayerSpirits: async function() {
                let week = new Week(this.week);
                this.playerSpirits = await week.playerSpirits().where('hero-race', this.hero.heroRace.name).$get();
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
                '_currentWeek',
                '_hero'
            ])
        },
    }
</script>

<style scoped>

</style>