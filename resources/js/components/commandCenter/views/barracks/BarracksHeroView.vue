<template>
    <v-col cols="12">
        <v-row no-gutters>
            <v-col cols="12">
                <v-card class="my-2">
                    <v-card-title>{{ barracksHero.name }}</v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col cols="12">
                <HeroMeasurablesCard></HeroMeasurablesCard>
            </v-col>
        </v-row>
    </v-col>
</template>

<script>
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";
    import MeasurablePanel from "../../barracks/MeasurablePanel";
    import HeroMeasurablesCard from "../../barracks/HeroMeasurablesCard";

    export default {
        name: "BarracksHeroView",
        components: {HeroMeasurablesCard, MeasurablePanel},
        mixins: [
            barracksHeroMixin
        ]
    }
</script>

<style scoped>

</style>
