<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <v-card v-if="_squadSnapshot">
                    <TabbedItems :items="_squadSnapshot.heroSnapshots">
                        <template v-slot:tab="{item}">
                            {{item.name}}
                        </template>
                        <template v-slot:default="{item}">
                            <HeroSnapshotTabItem :hero-snapshot="item"></HeroSnapshotTabItem>
                        </template>
                    </TabbedItems>
                </v-card>
                <v-skeleton-loader v-else
                    type="table-heading, list-item-two-line, image, table-tfoot"
                ></v-skeleton-loader>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <div v-if="_historicCampaignStopsLoaded">
                    <HistoricCampaignStopCard
                        v-for="(campaignStop, uuid) in _historicCampaignStops"
                        :key="uuid"
                        :historic-campaign-stop="campaignStop"
                        class="mb-2"
                    ></HistoricCampaignStopCard>
                </div>
                <v-skeleton-loader v-else
                   type="table-heading, list-item-two-line, image, table-tfoot"
                ></v-skeleton-loader>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import TabbedItems from "../../global/TabbedItems";
    import HeroSnapshotTabItem from "../../campaign/HeroSnapshotTabItem";
    import HistoricCampaignStopCard from "../../campaign/HistoricCampaignStopCard";

    export default {
        name: "HistoricCampaignView",
        components: {HistoricCampaignStopCard, HeroSnapshotTabItem, TabbedItems},
        data() {
            return {
                heroSnapshotTab: null
            }
        },
        computed: {
            ...mapGetters([
                '_focusedCampaign',
                '_squadSnapshot',
                '_historicCampaignStopsLoaded',
                '_historicCampaignStops'
            ]),
        }
    }
</script>

<style scoped>

</style>
