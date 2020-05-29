<template>
    <SquadReadinessPanel name="campaign" :readiness="readiness"></SquadReadinessPanel>
</template>

<script>
    import { mapGetters } from 'vuex'
    import SquadReadinessPanel from "./SquadReadinessPanel";
    export default {
        name: "CampaignReadinessPanel",
        components: {SquadReadinessPanel},
        computed: {
            ...mapGetters([
                '_currentCampaign',
                '_squad',
            ]),
            readiness() {
                if (! this._currentCampaign) {
                    return 'none';
                }
                if (this._currentCampaign.campaignStops.length < this._squad.questsPerWeek) {
                    return 'partial'
                }

                let unfilledStops = this._currentCampaign.campaignStops.filter((campaignStop) => this._squad.sideQuestsPerQuest > campaignStop.sideQuestUuids.length);
                if (unfilledStops.length > 0) {
                    return 'partial';
                }
                return 'full';
            }
        }
    }
</script>

<style scoped>

</style>
