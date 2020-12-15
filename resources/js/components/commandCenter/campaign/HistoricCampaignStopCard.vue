<template>
    <v-card outlined>
        <v-card-title>
            {{historicCampaignStop.name}}
        </v-card-title>
        <SideQuestResultExpandPanel
            v-for="(sideQuestResult, uuid) in historicCampaignStop.sideQuestResults"
            :key="uuid"
            :side-quest-result="sideQuestResult"
            class="mb-1 mx-1"
        ></SideQuestResultExpandPanel>
    </v-card>
</template>

<script>
    import HistoricCampaignStop from "../../../models/HistoricCampaignStop";
    import SideQuestResultExpandPanel from "./SideQuestResultExpandPanel";

    export default {
        name: "HistoricCampaignStopCard",
        components: {SideQuestResultExpandPanel},
        props: {
            historicCampaignStop: {
                type: HistoricCampaignStop,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
