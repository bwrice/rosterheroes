<template>
    <v-container>
        <template v-if="_barracksLoading">
            <LoadingOverlay :show-overlay="_barracksLoading"></LoadingOverlay>
        </template>
        <template v-else>
            <v-row>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                    <HeroesCard :heroes="_heroes"></HeroesCard>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                    <v-row no-gutters>
                        <v-col cols="12">
                            <MobileStorageCard></MobileStorageCard>
                        </v-col>
                        <v-col cols="12">
                            <LocalStashCard></LocalStashCard>
                        </v-col>
                        <v-col cols="12">
                            <Treasury></Treasury>
                        </v-col>
                        <v-col cols="12">
                            <SpellLibraryCard :spells="_spellLibrary"></SpellLibraryCard>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </template>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';
    import MobileStorageCard from "../../barracks/MobileStorageCard";
    import HeroesCard from "../../barracks/HeroesCard";
    import TwoColumnLayout from "../../layouts/TwoColumnLayout";
    import LoadingOverlay from "../../global/LoadingOverlay";
    import SpellLibraryCard from "../../barracks/SpellLibraryCard";
    import Treasury from "../../barracks/Treasury";
    import LocalStashCard from "./LocalStashCard";

    export default {
        name: "BarracksMain",
        components: {
            LocalStashCard,
            Treasury,
            SpellLibraryCard,
            LoadingOverlay,
            TwoColumnLayout,
            HeroesCard,
            MobileStorageCard
        },

        computed: {
            ...mapGetters([
                '_heroes',
                '_barracksLoading',
                '_spellLibrary',
            ])
        }
    }
</script>

<style scoped>

</style>
