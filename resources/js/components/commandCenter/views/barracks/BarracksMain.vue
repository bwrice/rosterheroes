<template>
    <v-container>
        <template v-if="_barracksLoading">
            <LoadingOverlay :show-overlay="_barracksLoading"></LoadingOverlay>
        </template>
        <template v-else>
            <v-row>
                <v-col cols="12" offset-sm="2" sm="8" md="6" offset-md="0" lg="5" offset-lg="1" xl="4" offset-xl="2">
                    <SquadSummaryCard></SquadSummaryCard>
                    <HeroesCard :heroes="_heroes"></HeroesCard>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" md="6" offset-md="0"  lg="5" xl="4">
                    <MobileStorageCard></MobileStorageCard>
                    <LocalStashCard></LocalStashCard>
                    <Treasury></Treasury>
                    <SpellLibraryCard :spells="_spellLibrary"></SpellLibraryCard>
                </v-col>
            </v-row>
        </template>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';
    import MobileStorageCard from "../../barracks/MobileStorageCard";
    import HeroesCard from "../../barracks/HeroesCard";
    import LoadingOverlay from "../../global/LoadingOverlay";
    import SpellLibraryCard from "../../barracks/SpellLibraryCard";
    import Treasury from "../../barracks/Treasury";
    import LocalStashCard from "../../barracks/LocalStashCard";
    import SquadSummaryCard from "../../barracks/SquadSummaryCard";

    export default {
        name: "BarracksMain",
        components: {
            SquadSummaryCard,
            LocalStashCard,
            Treasury,
            SpellLibraryCard,
            LoadingOverlay,
            HeroesCard,
            MobileStorageCard
        },

        computed: {
            ...mapGetters([
                '_heroes',
                '_barracksLoading',
                '_spellLibrary'
            ])
        }
    }
</script>

<style scoped>

</style>
