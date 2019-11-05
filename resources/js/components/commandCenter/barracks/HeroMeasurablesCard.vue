<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-row no-gutters align="center">
                <span class="title font-weight-thin">ATTRIBUTES</span>
            </v-row>
            <MeasurablePanel
                v-for="(attribute, uuid) in attributes"
                :measurable="attribute"
                :key="attribute.uuid"
                :hero="hero"
            ></MeasurablePanel>
            <v-row no-gutters align="center">
                <span class="title font-weight-thin">RESOURCES</span>
            </v-row>
            <MeasurablePanel
                v-for="(resource, uuid) in resources"
                :measurable="resource"
                :key="resource.uuid"
                :hero="hero"
            ></MeasurablePanel>
            <v-row no-gutters align="center">
                <span class="title font-weight-thin">QUALITIES</span>
            </v-row>
            <MeasurablePanel
                v-for="(quality, uuid) in qualities"
                :measurable="quality"
                :key="quality.uuid"
                :hero="hero"
            ></MeasurablePanel>
        </v-col>
    </v-row>
</template>

<script>

    import {barracksHeroMixin} from "../../../mixins/barracksHeroMixin";

    import MeasurablePanel from "./MeasurablePanel";
    import {mapGetters} from 'vuex';
    import Hero from "../../../models/Hero";

    export default {
        name: "HeroMeasurablesCard",
        components: {MeasurablePanel},

        props: {
            hero: {
                type: Hero,
                required: true
            }
        },

        mixins: [
            barracksHeroMixin
        ],

        computed: {
            ...mapGetters([
                '_measurableTypeByID'
            ]),

            attributes() {
                let self = this;
                return this.hero.measurables.filter(function (measurable) {
                    let measurableType = self._measurableTypeByID(measurable.measurableTypeID);
                    return measurableType.group === 'attribute';
                })
            },
            resources() {
                let self = this;
                return this.hero.measurables.filter(function (measurable) {
                    let measurableType = self._measurableTypeByID(measurable.measurableTypeID);
                    return measurableType.group === 'resource';
                })
            },
            qualities() {
                let self = this;
                return this.hero.measurables.filter(function (measurable) {
                    let measurableType = self._measurableTypeByID(measurable.measurableTypeID);
                    return measurableType.group === 'quality';
                })
            }
        },
    }
</script>

<style scoped>

</style>
