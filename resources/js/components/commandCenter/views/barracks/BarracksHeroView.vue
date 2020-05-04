<template>
    <v-container>
        <template v-if="_barracksLoading">
            <LoadingOverlay :show-overlay="_barracksLoading"></LoadingOverlay>
        </template>
        <template v-else>
            <v-row>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                    <HeroHeader :hero="hero"></HeroHeader>
                    <HeroGearCard :hero="hero"></HeroGearCard>
                </v-col>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                    <v-row no-gutters>
                        <v-col cols="12" class="pb-3">
                            <HeroMeasurablesCard :hero="hero"></HeroMeasurablesCard>
                        </v-col>
                        <v-col cols="12" class="pt-3">
                            <HeroSpellsCard :hero="hero"></HeroSpellsCard>
                        </v-col>
                    </v-row>
                </v-col>
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
    import HeroSpellsCard from "../../barracks/HeroSpellsCard";

    export default {
        name: "BarracksHeroView",
        components: {
            HeroSpellsCard,
            LoadingOverlay,
            HeroHeader,
            HeroGearCard,
            HeroMeasurablesCard,
            MeasurablePanel
        },
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
