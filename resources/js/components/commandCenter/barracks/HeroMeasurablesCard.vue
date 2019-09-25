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
                if (this.barracksHeroFromRoute) {
                    return this.barracksHeroFromRoute.measurables.filter(function (measurable) {
                        return measurable.measurableType.group === 'attribute';
                    })
                }
                return [];
            },
            resources() {
                if (this.barracksHeroFromRoute) {
                    return this.barracksHeroFromRoute.measurables.filter(function (measurable) {
                        return measurable.measurableType.group === 'resource';
                    })
                }
                return [];
            },
            qualities() {
                if (this.barracksHeroFromRoute) {
                    return this.barracksHeroFromRoute.measurables.filter(function (measurable) {
                        return measurable.measurableType.group === 'quality';
                    })
                }
                return [];
            }
        }
    }
</script>

<style scoped>

</style>
