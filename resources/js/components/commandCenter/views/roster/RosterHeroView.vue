<template>
    <SingleColumnLayout>
        <template v-slot:column-one>
            <v-row no-gutters>
                <v-col cols="12">
                    <HeroRosterCard :hero="hero">
                        <template slot="body">
                            <div class="mx-1" v-if="hero.playerSpirit">
                                <PlayerSpiritPanel :player-spirit="hero.playerSpirit">
                                    <template v-slot:spirit-actions>
                                        <RemoveSpiritButton
                                            :hero="hero"
                                            :player-spirit="hero.playerSpirit"
                                        >
                                        </RemoveSpiritButton>
                                    </template>
                                </PlayerSpiritPanel>
                            </div>
                            <v-row v-else no-gutters justify="center" align="center">
                                <v-col cols="12">
                                    <v-sheet color="rgba(0, 0, 0, .3)" class="mx-2 my-1">
                                        <v-row no-gutters justify="center" align="center">
                                            <span class="title pa-2 font-weight-thin">(empty)</span>
                                        </v-row>
                                    </v-sheet>
                                </v-col>
                            </v-row>
                        </template>
                    </HeroRosterCard>
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-col cols="12">
                    <PaginationBlock
                        :items="playerSpiritsForHero"
                        :items-per-page="itemsPerPage"
                        :loading="_loadingSpirits"
                        :search="search"
                        no-results-text="No Spirits Match Criteria"
                        empty-text="No Spirits Found"
                    >
                        <template v-slot:default="slotProps">
                            <PlayerSpiritPanel :player-spirit="slotProps.item">
                                <template v-slot:spirit-actions>
                                    <AddSpiritButton :hero="hero" :player-spirit="slotProps.item"></AddSpiritButton>
                                </template>
                            </PlayerSpiritPanel>
                        </template>
                    </PaginationBlock>
                </v-col>
            </v-row>
        </template>
    </SingleColumnLayout>
</template>

<script>
    import * as jsSearch from 'js-search';

    import { mapGetters } from 'vuex';

    import PlayerSpiritPanel from '../../roster/PlayerSpiritPanel';
    import AddSpiritButton from "../../roster/AddSpiritButton";
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import HeroRosterCard from "../../roster/HeroRosterCard";
    import SingleColumnLayout from "../../layouts/SingleColumnLayout";
    import PaginationBlock from "../../global/PaginationBlock";

    export default {
        name: "RosterHeroView",

        components: {
            PaginationBlock,
            SingleColumnLayout,
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

        data() {
            return {
                search: {
                    label: 'Search Player Spirits',
                    search: function (items, input) {
                        let search = new jsSearch.Search('uuid');
                        search.addIndex(['playerGameLog', 'player', 'firstName']);
                        search.addIndex(['playerGameLog', 'player', 'lastName']);
                        search.addDocuments(items);
                        return search.search(input);
                    }
                }
            }
        },

        computed: {
            ...mapGetters([
                '_squad',
                '_currentWeek',
                '_focusedHero',
                '_loadingSpirits',
                '_playerSpirits',
                '_heroRaceByID'
            ]),
            rosterPage() {
                return '/command-center/' + this.$route.params.squadSlug + '/roster' ;
            },
            hero() {
                return this._focusedHero(this.$route);
            },
            itemsPerPage() {
                let items = Math.ceil(window.innerHeight/90) - 4;
                return Math.max(items, 3);
            },
            playerSpiritsForHero() {
                let heroPositionIDs = this._heroRaceByID(this.hero.heroRaceID).positionIDs;
                let filtered = this._playerSpirits.filter(function (playerSpirit) {
                    let matchingIDs = playerSpirit.playerGameLog.player.positionIDs.filter(playerPositionID => heroPositionIDs.includes(playerPositionID));
                    return matchingIDs.length > 0;
                });
                if (this.hero.playerSpirit) {
                    let spiritUuid = this.hero.playerSpirit.uuid;
                    filtered = filtered.filter(function(playerSpirit) {
                        return playerSpirit.uuid !== spiritUuid;
                    })
                }
                return filtered;
            },
            filteredSpirits() {
                if (this.spiritSearch && this.spiritSearch.length) {
                    let search = new jsSearch.Search('uuid');
                    search.addIndex(['playerGameLog', 'player', 'firstName']);
                    search.addIndex(['playerGameLog', 'player', 'lastName']);
                    search.addDocuments(this.playerSpiritsForHero);
                    return search.search(this.spiritSearch);
                } else {
                    return this.playerSpiritsForHero;
                }
            },
        },
    }
</script>

<style scoped>

</style>
