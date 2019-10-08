<template>
    <TwoColumnLayout>
        <template v-slot:column-one>
            <v-row>
                <v-col cols="12" class="pt-0">
                    <HeroHeader :hero="hero"></HeroHeader>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <HeroGearCard :hero="hero"></HeroGearCard>
                </v-col>
            </v-row>
        </template>
        <template v-slot:column-two>
            <v-row>
                <v-col cols="12" class="pt-0">
                    <HeroMeasurablesCard></HeroMeasurablesCard>
                </v-col>
            </v-row>
        </template>
    </TwoColumnLayout>
</template>

<script>
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";
    import MeasurablePanel from "../../barracks/MeasurablePanel";
    import HeroMeasurablesCard from "../../barracks/HeroMeasurablesCard";
    import HeroGearCard from "../../barracks/gear/HeroGearCard";
    import BaseView from "../BaseView";
    import TwoColumnLayout from "../../layouts/TwoColumnLayout";
    import HeroHeader from "../../barracks/HeroHeader";

    import {mapGetters} from 'vuex';

    export default {
        name: "BarracksHeroView",
        components: {HeroHeader, TwoColumnLayout, BaseView, HeroGearCard, HeroMeasurablesCard, MeasurablePanel},
        mixins: [
            barracksHeroMixin
        ],
        computed: {
            ...mapGetters([
                '_focusedBarracksHero'
            ]),
            hero() {
                return this._focusedBarracksHero(this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
