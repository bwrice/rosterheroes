<template>
    <v-sheet color="#524c59" class="py-1 px-2 mb-1">
        <v-row no-gutters justify="center" class="pb-1">
            <span class="title rh-op-90">{{historicCampaign.description}}</span>
        </v-row>
        <v-sheet
            v-for="(campaignStopResult, uuid) in historicCampaign.campaignStopResults"
            :key="uuid"
            color="rgba(0,0,0,.2)"
            class="px-2 py-1 mb-1"

        >
            <v-row
                no-gutters
                justify="space-between"
                align="center"
            >
                <span class="subtitle-2 rh-op-85">{{campaignStopResult.questName}}</span>
                <v-btn
                    :to="campaignStopResultRoute(campaignStopResult.uuid)"
                    color="primary"
                >
                    view
                </v-btn>
            </v-row>
        </v-sheet>
    </v-sheet>
</template>

<script>
    import HistoricCampaign from "../../../models/HistoricCampaign";

    export default {
        name: "HistoricCampaignPanel",
        props: {
            historicCampaign: {
                type: HistoricCampaign,
                required: true
            }
        },
        methods: {
            campaignStopResultRoute(campaignStopUuid) {
                return {
                    name: 'quest-result',
                    params: {
                        campaignStopUuid: campaignStopUuid
                    }
                }
            }
        }

    }
</script>

<style scoped>

</style>
