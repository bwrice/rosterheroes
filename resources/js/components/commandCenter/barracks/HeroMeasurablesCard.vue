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
<!--    <MeasurablesPanelGroup :title="'Attributes'" :measurables="attributes"></MeasurablesPanelGroup>-->
<!--    <v-card>-->
<!--        <v-card>-->
<!--            <v-card-title>Resources</v-card-title>-->
<!--            <MeasurablePanel-->
<!--                v-for="(resource, uuid) in resources"-->
<!--                :key="uuid"-->
<!--                :measurable="resource"-->
<!--            ></MeasurablePanel>-->
<!--        </v-card>-->
<!--        <v-card>-->
<!--            <v-card-title>Qualities</v-card-title>-->
<!--            <MeasurablePanel-->
<!--                v-for="(quality, uuid) in qualities"-->
<!--                :key="uuid"-->
<!--                :measurable="quality"-->
<!--            ></MeasurablePanel>-->
<!--        </v-card>-->
<!--    </v-card>-->
</template>

<script>

    import {barracksHeroMixin} from "../../../mixins/barracksHeroMixin";

    import MeasurablePanel from "./MeasurablePanel";
    import MeasurablesPanelGroup from "./MeasurablesPanelGroup";

    export default {
        name: "HeroMeasurablesCard",
        components: {MeasurablesPanelGroup, MeasurablePanel},

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
