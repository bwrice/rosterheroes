<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" offset-lg="1" lg="5" offset-xl="2" xl="4">
                <EssenceAndRosterColumn :heroes="_heroes"></EssenceAndRosterColumn>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
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

                                    <!-- TODO: Slot in dropdown to select hero to embody -->
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
    import EssenceAndRosterColumn from "../../roster/EssenceAndRosterColumn";

    export default {
        name: "RosterMain",
        components: {
            EssenceAndRosterColumn,
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
