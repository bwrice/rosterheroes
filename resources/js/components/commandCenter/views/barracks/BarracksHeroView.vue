<template>
    <TwoColumnLayout>
        <template v-slot:column-one>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>{{ heroName }}</v-card-title>
                    </v-card>
                </v-col>
                <v-col cols="12">
                    <HeroGearCard></HeroGearCard>
                </v-col>
            </v-row>
        </template>
        <template v-slot:column-two>
            <v-row>
                <v-col cols="12">
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

    export default {
        name: "BarracksHeroView",
        components: {TwoColumnLayout, BaseView, HeroGearCard, HeroMeasurablesCard, MeasurablePanel},
        mixins: [
            barracksHeroMixin
        ],
        computed: {
            heroName() {
                if (this.barracksHeroFromRoute) {
                    return this.barracksHeroFromRoute.name;
                }
                return '';
            }
        }
    }
</script>

<style scoped>

</style>
