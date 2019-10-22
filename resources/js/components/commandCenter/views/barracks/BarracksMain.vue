<template>
    <v-container>
        <template v-if="_barracksLoading">
            <LoadingOverlay :show-overlay="_barracksLoading"></LoadingOverlay>
        </template>
        <template v-else>
            <v-row>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="4" xl="3">
                    <HeroesCard :heroes="_heroes"></HeroesCard>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="4" xl="3">
                    <MobileStorageCard :mobile-storage="_mobileStorage"></MobileStorageCard>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="4" xl="3">
                    <SpellLibraryCard :spells="_spellLibrary"></SpellLibraryCard>
                </v-col>
            </v-row>
        </template>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';
    import BarracksHeroCard from "../../barracks/HeroSummaryPanel";
    import MobileStorageCard from "../../barracks/MobileStorageCard";
    import HeroesCard from "../../barracks/HeroesCard";
    import TwoColumnLayout from "../../layouts/TwoColumnLayout";
    import LoadingOverlay from "../../global/LoadingOverlay";
    import SpellLibraryCard from "../../barracks/SpellLibraryCard";

    export default {
        name: "BarracksMain",
        components: {SpellLibraryCard, LoadingOverlay, TwoColumnLayout, HeroesCard, MobileStorageCard, BarracksHeroCard},

        computed: {
            ...mapGetters([
                '_mobileStorage',
                '_heroes',
                '_barracksLoading',
                '_spellLibrary'
            ])
        }
    }
</script>

<style scoped>

</style>
