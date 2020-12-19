<template>
    <v-card>
        <v-sheet
            color="rgba(255,255,255, 0.2)"
            class="pa-1"
        >
            <v-text-field
                v-model="searchInput"
                clearable
                flat
                solo-inverted
                hide-details
                prepend-inner-icon="search"
                label="Search Player Spirits"
            ></v-text-field>

        </v-sheet>
        <div v-if="_loadingSpirits">
            <v-skeleton-loader
                type="list-item-two-line, divider"
                :height="spiritHeight"
                v-for="n in numberOfSpiritsToShow"
                :key="n"
            >
            </v-skeleton-loader>
        </div>
        <v-virtual-scroll
            v-else
            :items="filteredSpirits"
            :height="poolHeight"
            :item-height="spiritHeight"
            :bench="4"
        >
            <template v-slot:default="{ item }">
                <PlayerSpiritPanel
                    :player-spirit="item"
                >
                    <template v-slot:spirit-actions>
                        <AddSpiritButton
                            :hero="hero"
                            :player-spirit="item"
                        ></AddSpiritButton>
                    </template>
                </PlayerSpiritPanel>
                <v-divider></v-divider>
            </template>
        </v-virtual-scroll>
    </v-card>
</template>

<script>
    import PlayerSpiritPanel from "./PlayerSpiritPanel";
    import Hero from "../../../models/Hero";
    import AddSpiritButton from "./AddSpiritButton";

    import * as jsSearch from 'js-search';
    import {mapGetters} from 'vuex';

    export default {
        name: "PlayerSpiritPool",
        components: {AddSpiritButton, PlayerSpiritPanel},
        props: {
            hero: {
                type: Hero,
                default: {
                    return: null
                }
            }
        },
        data() {
            return {
                spiritHeight: 76,
                searchInput: '',
                filteredSpirits: [],
                debounceFilteredSpirits: _.debounce(this.filterSpiritsForSearch, 400)
            }
        },
        created() {
            this.filteredSpirits = this.baseFilteredSpirits;
        },
        watch: {
            searchInput(newValue) {
                this.debounceFilteredSpirits(newValue);
            },
            baseFilteredSpirits(newValue) {
                this.searchInput = '';
                this.filteredSpirits = newValue;
            }
        },
        methods: {
            filterSpiritsForSearch(searchInput) {
                if (searchInput && searchInput.length > 0) {
                    let search = new jsSearch.Search('uuid');
                    search.addIndex(['playerGameLog', 'player', 'firstName']);
                    search.addIndex(['playerGameLog', 'player', 'lastName']);
                    search.addDocuments(this.baseFilteredSpirits);
                    this.filteredSpirits = search.search(searchInput);
                } else {
                    this.filteredSpirits = this.baseFilteredSpirits;
                }
            }
        },
        computed: {
            ...mapGetters([
                '_heroes',
                '_loadingSpirits',
                '_playerSpirits',
                '_heroRaceByID'
            ]),
            poolHeight() {
                return this.spiritHeight * this.numberOfSpiritsToShow;
            },
            numberOfSpiritsToShow() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 4;
                    default:
                        return 6;
                }
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
            baseFilteredSpirits() {
                let filtered = [];
                if (this.hero.uuid) {
                    filtered = this.playerSpiritsForHero;
                } else {
                    filtered = this._playerSpirits;
                }
                return filtered;
            }
        }
    }
</script>

<style scoped>

</style>
