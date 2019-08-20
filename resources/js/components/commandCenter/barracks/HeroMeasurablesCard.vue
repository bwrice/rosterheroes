<template>
    <v-card>
        <v-card>
            <v-card-title>Attributes</v-card-title>
            <MeasurablePanel
                v-for="(attribute, uuid) in attributes"
                :key="uuid"
                :measurable="attribute"
                @measurableClicked="setFocusedMeasurable"
            ></MeasurablePanel>
        </v-card>
        <v-card>
            <v-card-title>Resources</v-card-title>
            <MeasurablePanel
                v-for="(resource, uuid) in resources"
                :key="uuid"
                :measurable="resource"
                @measurableClicked="setFocusedMeasurable"
            ></MeasurablePanel>
        </v-card>
        <v-card>
            <v-card-title>Qualities</v-card-title>
            <MeasurablePanel
                v-for="(quality, uuid) in qualities"
                :key="uuid"
                :measurable="quality"
                @measurableClicked="setFocusedMeasurable"
            ></MeasurablePanel>
        </v-card>
        <v-dialog
            v-model="measurableFocused"
            max-width="600"
        >
                <v-card>
                    <v-card-title class="headline">{{measurableName}}: {{potentialAmount}}(+{{measurableRaiseAmount}})</v-card-title>
                    <v-card-actions>
                        <v-container>
                            <v-row no-gutters>
                                <v-col cols="6">
                                    <v-text-field
                                        outlined
                                        solo
                                        type="number"
                                        v-model="measurableRaiseAmount"
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col cols="6">
                                    <v-row :justify="'center'">
                                        <v-btn
                                            fab
                                            small
                                            @click="increaseRaiseAmount"
                                        >
                                            <v-icon dark>add</v-icon>
                                        </v-btn>
                                        <v-btn
                                            fab
                                            small
                                            @click="lowerRaiseAmount"
                                        >
                                            <v-icon dark>remove</v-icon>
                                        </v-btn>
                                    </v-row>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="6">
                                    <v-row :justify="'center'">
                                        Available: 48304
                                    </v-row>
                                </v-col>
                                <v-col cols="6">
                                    <v-row :justify="'center'">
                                        Cost: 9834
                                    </v-row>
                                </v-col>
                            </v-row>
                            <v-row no gutters>
                                <v-btn
                                    color="primary"
                                    block
                                >
                                    Raise {{measurableName}}
                                </v-btn>
                            </v-row>
                        </v-container>
                    </v-card-actions>
                </v-card>
        </v-dialog>
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

        data() {
            return {
                focusedMeasurable: {
                    measurable_type: {
                        name: ''
                    }
                },
                measurableFocused: false,
                measurableRaiseAmount: 1
            }
        },

        methods: {
            setFocusedMeasurable(measurable) {
                this.focusedMeasurable = measurable;
                this.measurableFocused = true;
            },
            lowerRaiseAmount() {

            },
            increaseRaiseAmount() {

            }
        },

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
            },
            measurableName() {
                return this.focusedMeasurable.measurable_type.name.toUpperCase();
            },
            potentialAmount() {
                let potential = this.focusedMeasurable.current_amount;
                if (this.raiseAmount) {
                    potential += this.raiseAmount;
                }
                return potential;
            },
            raiseAmount() {
                return parseInt(this.measurableRaiseAmount);
            }
        }
    }
</script>

<style scoped>

</style>
