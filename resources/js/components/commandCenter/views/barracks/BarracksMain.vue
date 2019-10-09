<template>
    <TwoColumnLayout :loading="_barracksLoading">
        <template v-slot:column-one>
            <v-row>
                <v-col cols="12" class="pt-0 pb-0">
                    <HeroesCard :heroes="_heroes"></HeroesCard>
                </v-col>
            </v-row>
        </template>
        <template v-slot:column-two>
            <v-row>
                <v-col cols="12" class="pt-0">
                    <MobileStorageCard :mobile-storage="_mobileStorage"></MobileStorageCard>
                </v-col>
            </v-row>
        </template>
    </TwoColumnLayout>
</template>

<script>

    import {mapGetters} from 'vuex';
    import BarracksHeroCard from "../../barracks/HeroSummaryPanel";
    import MobileStorageCard from "../../barracks/MobileStorageCard";
    import HeroesCard from "../../barracks/HeroesCard";
    import TwoColumnLayout from "../../layouts/TwoColumnLayout";

    export default {
        name: "BarracksMain",
        components: {TwoColumnLayout, HeroesCard, MobileStorageCard, BarracksHeroCard},

        computed: {
            ...mapGetters([
                '_mobileStorage',
                '_heroes',
                '_barracksLoading'
            ])
        }
    }
</script>

<style scoped>

</style>
