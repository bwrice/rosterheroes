<template>
    <v-col cols="12">
        <v-row no-gutters>
            <v-col cols="12">
                <v-card class="my-2">
                    <v-card-title>{{ heroName }}</v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
<!--                <GearCard></GearCard>-->
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
    import GearCard from "../../barracks/gear/HeroGearCard";

    export default {
        name: "BarracksHeroView",
        components: {GearCard, HeroMeasurablesCard, MeasurablePanel},
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
