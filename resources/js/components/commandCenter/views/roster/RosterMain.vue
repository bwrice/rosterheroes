<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" offset-lg="2" lg="4" offset-xl="3" xl="3">
                <v-row class="no-gutters">
                    <v-col cols="12">
                        <v-row no-gutters class="mb-2">
                            <span class="title font-weight-thin">SPIRIT ESSENCE</span>
                        </v-row>
                    </v-col>
                    <v-col cols="12">
                        <SpiritEssenceCard></SpiritEssenceCard>
                    </v-col>
                    <v-col cols="12">
                        <v-row no-gutters class="my-2">
                            <span class="title font-weight-thin">ROSTER</span>
                        </v-row>
                    </v-col>
                    <v-col cols="12" v-for="(hero, uuid) in _heroes" :key="uuid">
                        <HeroRosterCard :hero="hero">
                            <template slot="body">
                                <div class="mx-1" v-if="hero.playerSpirit">
                                    <PlayerSpiritPanel :player-spirit="hero.playerSpirit">
                                        <template v-slot:spirit-actions>
                                            <EditSpiritButton :hero="hero"></EditSpiritButton>
                                            <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpirit"></RemoveSpiritButton>
                                        </template>
                                    </PlayerSpiritPanel>
                                </div>
                                <v-row v-else justify="center" align="center" no-gutters class="mx-2">
                                    <AddSpiritRouterButton :hero-slug="hero.slug" :btn-classes="{'mx-2': true}"></AddSpiritRouterButton>
                                </v-row>
                            </template>
                        </HeroRosterCard>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="4" xl="3">
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row no-gutters class="mb-2">
                            <span class="title font-weight-thin">SPIRIT POOL</span>
                        </v-row>
                    </v-col>
                    <v-col cols="12">
                        <PaginationBlock
                            :items="_playerSpirits"
                            :items-per-page="itemsPerPage"
                            :search="search"
                            no-results-text="No Spirits Match Criteria"
                            empty-text="No Spirits Found"
                        >
                            <template v-slot:default="slotProps">
                                <PlayerSpiritPanel :player-spirit="slotProps.item">
<!--                                    <template v-slot:spirit-actions>-->
<!--                                        <AddSpiritButton :hero="hero" :player-spirit="slotProps.item"></AddSpiritButton>-->
<!--                                    </template>-->
                                </PlayerSpiritPanel>
                            </template>
                        </PaginationBlock>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import EditSpiritButton from "../../roster/EditSpiritButton";
    import PlayerSpiritPanel from "../../roster/PlayerSpiritPanel";

    import * as jsSearch from 'js-search';
    import { mapGetters } from 'vuex'

    import SingleColumnLayout from "../../layouts/SingleColumnLayout";
    import AddSpiritRouterButton from "../../global/AddSpiritRouterButton";
    import SpiritEssenceCard from "../../roster/SpiritEssenceCard";
    import PaginationBlock from "../../global/PaginationBlock";
    import HeroRosterCard from '../../roster/HeroRosterCard';
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";

    export default {
        name: "RosterMain",
        components: {
            PaginationBlock,
            SpiritEssenceCard,
            AddSpiritRouterButton,
            SingleColumnLayout,
            HeroRosterCard,
            EditSpiritButton,
            RemoveSpiritButton,
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
                '_heroes',
                '_playerSpirits',
            ]),
            itemsPerPage() {
                let items = Math.ceil(window.innerHeight/90) - 4;
                return Math.max(items, 3);
            },
        },
    }
</script>

<style scoped>

</style>
