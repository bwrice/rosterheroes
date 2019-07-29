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

    export default {
        name: "HeroRosterView",
        props: ['hero'],

        components: {
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

        mounted() {
            this.updatePlayerSpiritsPool();
        },

        data: function() {
            return {
                playerSpirits: []
            }
        },

        methods: {
            ...mapActions([
                'setRosterFocusedHero'
            ]),
            unFocus: function() {
                this.setRosterFocusedHero(null);
            },
            getFocusedPlayerSpirit: function(playerSpirit) {
                return new PlayerSpirit(playerSpirit);
            },
            async updatePlayerSpiritsPool() {
                this.playerSpirits = await this._currentWeek.playerSpirits().where('hero-race', this.hero.heroRace.name).$get();
            },
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_currentWeek'
            ])
        },
    }
</script>

<style scoped>

</style>