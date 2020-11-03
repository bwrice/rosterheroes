<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                {{_focusedCampaign.uuid}}
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">

            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "HistoricCampaignView",
        computed: {
            ...mapGetters([
                '_focusedCampaign',
            ]),
        }
    }
</script>

<style scoped>

</style>
