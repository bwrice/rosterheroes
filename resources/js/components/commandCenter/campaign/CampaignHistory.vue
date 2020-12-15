<template>
    <CardSection title="CAMPAIGN HISTORY">
        <PaginationBlock
            v-if="historyAvailable"
            :items="_historicCampaigns"
            :items-per-page="5"
        >
            <template v-slot:default="slotProps">
                <HistoricCampaignPanel
                    :historic-campaign="slotProps.item"
                >
                </HistoricCampaignPanel>
            </template>
        </PaginationBlock>
        <v-sheet color="rgba(255,255,255, 0.25)" v-else>
            <v-row no-gutters class="pa-2" justify="center" align="center">
                <span class="rh-op-70 subtitle-1">No Campaign History Available</span>
            </v-row>
        </v-sheet>
    </CardSection>
</template>

<script>

    import {mapGetters} from 'vuex';
    import CardSection from "../global/CardSection";
    import PaginationBlock from "../global/PaginationBlock";
    import HistoricCampaignPanel from "./HistoricCampaignPanel";
    export default {
        name: "CampaignHistory",
        components: {HistoricCampaignPanel, PaginationBlock, CardSection},

        computed: {
            ...mapGetters([
                '_historicCampaigns',
            ]),
            historyAvailable() {
                return this._historicCampaigns.length > 0;
            }
        }
    }
</script>

<style scoped>

</style>
