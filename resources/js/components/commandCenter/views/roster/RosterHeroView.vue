<template>
    <SingleColumnLayout>
        <template v-slot:column-one>
            <v-card>
                <v-card-text class="px-1 py-2">
                    <HeroRosterCard :hero="hero">
                        <template slot="body">
                            <PlayerSpiritPanel v-if="hero.playerSpirit" :player-spirit="hero.playerSpirit">
                                <template v-slot:spirit-actions>
                                    <RemoveSpiritButton
                                        :hero="hero"
                                        :player-spirit="hero.playerSpirit"
                                    >
                                    </RemoveSpiritButton>
                                </template>
                            </PlayerSpiritPanel>
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
                    <v-data-iterator
                        :items="filteredSpirits"
                        :items-per-page="itemsPerPage"
                        :page="page"
                        :loading="_rosterLoading"
                        hide-default-footer
                        row
                        no-gutters
                        no-data-text="No spirits match the criteria"
                        no-results-text="No spirits match the criteria"
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
                                class="my-1"
                            ></v-text-field>
                        </template>
                        <template v-slot:item="props">
                            <PlayerSpiritPanel :player-spirit="props.item">
                                <template v-slot:spirit-actions>
                                    <AddSpiritButton :hero="hero" :player-spirit="props.item"></AddSpiritButton>
                                </template>
                            </PlayerSpiritPanel>
                        </template>
                        <template v-slot:loading>
                            <v-row :justify="'center'" class="py-5">
                                <v-progress-circular indeterminate size="36"></v-progress-circular>
                            </v-row>
                        </template>
                        <template v-slot:footer>
                            <IteratorFooter :page="page" :number-of-pages="numberOfPages" @formerPage="decreasePage" @nextPage="increasePage">
                            </IteratorFooter>
                        </template>
                    </v-data-iterator>
                </v-card-text>
            </v-card>
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
    import IteratorFooter from "../../global/IteratorFooter";

    export default {
        name: "RosterHeroView",

        components: {
            IteratorFooter,
            SingleColumnLayout,
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

        mounted() {
            console.log("Height");
            console.log(window.innerHeight);
        },

        data() {
            return {
                spiritSearch: '',
                page: 1
            }
        },

        methods: {
            increasePage () {
                if (this.page + 1 <= this.numberOfPages) this.page += 1
            },
            decreasePage () {
                if (this.page - 1 >= 1) this.page -= 1
            },
        },

        computed: {
            ...mapGetters([
                '_squad',
                '_currentWeek',
                '_focusedHero',
                '_playerSpirits',
                '_heroRaceByID',
                '_rosterLoading'
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
            numberOfPages() {
                let spiritsCount = this.filteredSpirits.length;
                if (! spiritsCount) return 1;
                return Math.ceil(spiritsCount / this.itemsPerPage);
            },
            playerSpiritsForHero() {
                let heroPositionIDs = this._heroRaceByID(this.hero.heroRaceID).positionIDs;
                let filtered = this._playerSpirits.filter(function (playerSpirit) {
                    let matchingIDs = playerSpirit.player.positionIDs.filter(playerPositionID => heroPositionIDs.includes(playerPositionID));
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
                    search.addIndex(['player', 'firstName']);
                    search.addIndex(['player', 'lastName']);
                    search.addDocuments(this.playerSpiritsForHero);
                    return search.search(this.spiritSearch);
                } else {
                    return this.playerSpiritsForHero;
                }
            }
        },
    }
</script>

<style scoped>

</style>
