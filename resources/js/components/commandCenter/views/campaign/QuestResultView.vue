<template>
    <v-container>
        <v-row justify="center">
            <span class="headline">{{historicCampaignStop.questName}}</span>
        </v-row>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <v-row no-gutters>
                    <v-col cols="12" class="pb-3">
                    </v-col>
                    <v-col cols="12" class="pt-3">
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
        name: "QuestResultView",
        computed: {
            ...mapGetters([
                '_historicCampaignStopByUuid',
            ]),
            historicCampaignStop() {
                return this._historicCampaignStopByUuid(this.$route.params.campaignStopUuid);
            }
        }
    }
</script>

<style scoped>

</style>
