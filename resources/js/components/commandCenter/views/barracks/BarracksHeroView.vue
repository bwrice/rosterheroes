<template>
    <v-container>
        <template v-if="_barracksLoading">
            <LoadingOverlay :show-overlay="_barracksLoading"></LoadingOverlay>
        </template>
        <template v-else>
            <v-row>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="0">
                    <HeroHeader :hero="hero"></HeroHeader>
                    <HeroGearCard :hero="hero"></HeroGearCard>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                    <HeroMeasurablesCard :hero="hero"></HeroMeasurablesCard>
                </v-col>
<!--                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="0">-->
<!--                    <SpellLibraryCard :spells="_spellLibrary"></SpellLibraryCard>-->
<!--                </v-col>-->
            </v-row>
        </template>
    </v-container>
</template>

<script>
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";
    import MeasurablePanel from "../../barracks/MeasurablePanel";
    import HeroMeasurablesCard from "../../barracks/HeroMeasurablesCard";
    import HeroGearCard from "../../barracks/gear/HeroGearCard";
    import TwoColumnLayout from "../../layouts/TwoColumnLayout";
    import HeroHeader from "../../barracks/HeroHeader";

    import {mapGetters} from 'vuex';
    import LoadingOverlay from "../../global/LoadingOverlay";

    export default {
        name: "BarracksHeroView",
        components: {LoadingOverlay, HeroHeader, TwoColumnLayout, HeroGearCard, HeroMeasurablesCard, MeasurablePanel},
        mixins: [
            barracksHeroMixin
        ],
        computed: {
            ...mapGetters([
                '_focusedHero',
                '_barracksLoading'
            ]),
            hero() {
                return this._focusedHero(this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
