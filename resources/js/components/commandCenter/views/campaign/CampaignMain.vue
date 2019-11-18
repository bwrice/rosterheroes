<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <AvailableQuestsSection></AvailableQuestsSection>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import AvailableQuestsSection from "../../campaign/AvailableQuestsSection";
    export default {
        name: "CampaignMain",
        components: {AvailableQuestsSection},
        created: () => {
            console.log("Campaign Created!");
        }
    }
</script>

<style scoped>

</style>
