<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <CurrentCampaignCard></CurrentCampaignCard>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <AvailableQuestsSection></AvailableQuestsSection>
                <CampaignHistory></CampaignHistory>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import AvailableQuestsSection from "../../campaign/AvailableQuestsSection";
    import CurrentCampaignCard from "../../campaign/CurrentCampaignCard";
    import CampaignHistory from "../../campaign/CampaignHistory";
    export default {
        name: "CampaignMain",
        components: {CampaignHistory, CurrentCampaignCard, AvailableQuestsSection},
    }
</script>

<style scoped>

</style>
