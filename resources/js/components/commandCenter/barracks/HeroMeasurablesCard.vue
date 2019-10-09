<template>
    <v-card class="py-1">
        <v-row no-gutters align="center">
            <span class="title font-weight-light px-3">Attributes</span>
        </v-row>
        <MeasurablePanel
            v-for="(attribute, uuid) in attributes"
            :measurable="attribute"
            :key="attribute.uuid"
        ></MeasurablePanel>
        <v-row no-gutters align="center">
            <span class="title font-weight-light px-3">Resources</span>
        </v-row>
        <MeasurablePanel
            v-for="(resource, uuid) in resources"
            :measurable="resource"
            :key="resource.uuid"
        ></MeasurablePanel>
        <v-row no-gutters align="center">
            <span class="title font-weight-light px-3">Qualities</span>
        </v-row>
        <MeasurablePanel
            v-for="(quality, uuid) in qualities"
            :measurable="quality"
            :key="quality.uuid"
        ></MeasurablePanel>
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
