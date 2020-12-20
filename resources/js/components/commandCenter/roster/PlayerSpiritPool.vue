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
            v-else-if="filteredSpirits.length > 0"
            :items="filteredSpirits"
            :height="poolHeight"
            :item-height="spiritHeight"
            :bench="4"
        >
            <template v-slot:default="{ item }">
                <PlayerSpiritPanel
                    :player-spirit="item"
                    :affordable="spiritAffordable(item)"
                >
                    <template v-slot:spirit-actions>
                        <AddSpiritButton
                            v-if="hero"
                            :hero="hero"
                            :player-spirit="item"
                        ></AddSpiritButton>

                        <EmbodyHeroSelect
                            v-else-if="_heroes.length > 0"
                            :player-spirit="item"
                        ></EmbodyHeroSelect>
                    </template>
                </PlayerSpiritPanel>
                <v-divider></v-divider>
            </template>
        </v-virtual-scroll>
        <v-row
            v-else
            justify="center"
            align="center"
            :style="'height: ' + poolHeight + 'px'"
            class="no-gutters">
            <span class="text-h6 text-lg-h5" style="color: rgba(255, 255, 255, 0.8)">No Player Spirits Found</span>
        </v-row>
    </v-card>
</template>

<script>
    import PlayerSpiritPanel from "./PlayerSpiritPanel";
    import AddSpiritButton from "./AddSpiritButton";

    import * as jsSearch from 'js-search';
    import {mapGetters} from 'vuex';
    import EmbodyHeroSelect from "./EmbodyHeroSelect";

    export default {
        name: "PlayerSpiritPool",
        components: {EmbodyHeroSelect, AddSpiritButton, PlayerSpiritPanel},
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
            },
            spiritAffordable(playerSpirit) {
                if (this.hero) {
                    let currentEssenceUsed = this.hero.playerSpirit ? this.hero.playerSpirit.essenceCost : 0;
                    return this._availableSpiritEssence + currentEssenceUsed >= playerSpirit.essenceCost;
                }
                return true;
            }
        },
        computed: {
            ...mapGetters([
                '_heroes',
                '_focusedHero',
                '_loadingSpirits',
                '_playerSpirits',
                '_heroRaceByID',
                '_availableSpiritEssence'
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
            hero() {
                return this._focusedHero(this.$route, true);
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
                if (this.hero) {
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
