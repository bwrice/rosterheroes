<template>
    <v-flex class="xs12">
        <v-card>
            <v-btn :to="rosterPage">
                <v-icon>arrow_back</v-icon>Back
            </v-btn>
            <HeroRosterCard :hero="rosterFocusedHero" v-if="rosterFocusedHero">
                <template slot="body">
                    <template v-if="rosterFocusedHero.playerSpirit">
                        <PlayerSpiritPanel :player-spirit="rosterFocusedHero.playerSpirit">
                            <template v-slot:spirit-actions>
                                <RemoveSpiritButton :hero="rosterFocusedHero" :player-spirit="rosterFocusedHero.playerSpirit"></RemoveSpiritButton>
                            </template>
                        </PlayerSpiritPanel>
                    </template>
                </template>
            </HeroRosterCard>
            <v-data-iterator
                    :items="playerSpiritsPool"
                    :loading="_rosterLoading"
                    hide-default-footer
                    row
                    wrap
            >
                <template v-slot:header>
                    <v-toolbar
                            class="mb-2"
                            color="secondary"
                            dark
                            text
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
                                <AddSpiritButton :hero="rosterFocusedHero" :player-spirit="props.item"></AddSpiritButton>
                            </template>
                        </PlayerSpiritPanel>
                    </v-flex>
                </template>
                <template v-slot:loading>
                    <v-row :justify="'center'" class="py-5">
                        <v-progress-circular indeterminate size="36"></v-progress-circular>
                    </v-row>
                </template>
            </v-data-iterator>
        </v-card>
    </v-flex>
</template>

<script>
    import { mapGetters } from 'vuex';

    import PlayerSpiritPanel from '../../roster/PlayerSpiritPanel';
    import AddSpiritButton from "../../roster/AddSpiritButton";
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import HeroRosterCard from "../../roster/HeroRosterCard";

    export default {
        name: "RosterHeroView",

        components: {
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

        data() {
            return {
                emptyHero: {
                    name: '',
                    playerSpirit: null,
                    heroRace: {
                        positions: []
                    }
                }
            }
        },

        computed: {
            ...mapGetters([
                '_squad',
                '_currentWeek',
                '_rosterHeroes',
                '_playerSpiritsPool',
                '_rosterLoading'
            ]),
            rosterPage() {
                return '/command-center/' + this.$route.params.squadSlug + '/roster' ;
            },
            rosterFocusedHero() {
                if ('roster-hero' === this.$route.name) {
                    let hero = this._rosterHeroes.find((hero) => hero.slug === this.$route.params.heroSlug);
                    if (hero) {
                        return hero;
                    }
                }

                return this.emptyHero;
            },
            playerSpiritsPool() {
                let heroPositionIDs = this.rosterFocusedHero.heroRace.positions.map(function (position) {
                    return position.id;
                });
                return this._playerSpiritsPool.filter(function (playerSpirit) {
                    let spiritPositionIDs = playerSpirit.player.positions.map(function (position) {
                        return position.id;
                    });
                    let filtered = spiritPositionIDs.filter(spiritPosID => heroPositionIDs.includes(spiritPosID));
                    return filtered.length > 0;
                });
            }
        },
    }
</script>

<style scoped>

</style>
