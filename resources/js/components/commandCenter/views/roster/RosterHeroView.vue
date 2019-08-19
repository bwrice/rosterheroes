<template>
    <v-col cols="12">
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
                    :items="filteredSpirits"
                    :loading="_rosterLoading"
                    row
                    no-gutters
            >
                <template v-slot:header>
                    <v-text-field
                        v-model="spiritSearch"
                        clearable
                        flat
                        solo-inverted
                        hide-details
                        prepend-inner-icon="search"
                        label="Search Player Spirits"
                    ></v-text-field>
                </template>
                <template v-slot:item="props">
                        <PlayerSpiritPanel :player-spirit="props.item">
                            <template v-slot:spirit-actions>
                                <AddSpiritButton :hero="rosterFocusedHero" :player-spirit="props.item"></AddSpiritButton>
                            </template>
                        </PlayerSpiritPanel>
                </template>
                <template v-slot:loading>
                    <v-row :justify="'center'" class="py-5">
                        <v-progress-circular indeterminate size="36"></v-progress-circular>
                    </v-row>
                </template>
            </v-data-iterator>
        </v-card>
    </v-col>
</template>

<script>
    import * as jsSearch from 'js-search';

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
                },
                spiritSearch: ''
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
            },
            filteredSpirits() {
                if (this.spiritSearch.length) {
                    let search = new jsSearch.Search('uuid');
                    search.addIndex(['player', 'full_name']);
                    search.addIndex(['game', 'homeTeam', 'name']);
                    search.addIndex(['game', 'awayTeam', 'name']);
                    search.addDocuments(this.playerSpiritsPool);
                    return search.search(this.spiritSearch);
                } else {
                    return this.playerSpiritsPool;
                }
            }
        },
    }
</script>

<style scoped>

</style>
