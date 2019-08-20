<template>
    <v-card>
        <v-card>
            <v-card-title>Attributes</v-card-title>
            <MeasurablePanel
                v-for="(attribute, uuid) in attributes"
                :key="uuid"
                :measurable="attribute"
            ></MeasurablePanel>
        </v-card>
        <v-card>
            <v-card-title>Resources</v-card-title>
            <MeasurablePanel
                v-for="(resource, uuid) in resources"
                :key="uuid"
                :measurable="resource"
            ></MeasurablePanel>
        </v-card>
        <v-card>
            <v-card-title>Qualities</v-card-title>
            <MeasurablePanel
                v-for="(quality, uuid) in qualities"
                :key="uuid"
                :measurable="quality"
            ></MeasurablePanel>
        </v-card>
    </v-card>
</template>

<script>

    import {barracksHeroMixin} from "../../../mixins/barracksHeroMixin";

    import MeasurablePanel from "./MeasurablePanel";

    export default {
        name: "HeroMeasurablesCard",
        components: {MeasurablePanel},

        mixins: [
            barracksHeroMixin
        ],

        computed: {
            attributes() {
                return this.barracksHero.measurables.filter(function (measurable) {
                    return measurable.measurable_type.group === 'attribute';
                })
            },
            resources() {
                return this.barracksHero.measurables.filter(function (measurable) {
                    return measurable.measurable_type.group === 'resource';
                })
            },
            qualities() {
                return this.barracksHero.measurables.filter(function (measurable) {
                    return measurable.measurable_type.group === 'quality';
                })
            }
        }
    }
</script>

<style scoped>

</style>
