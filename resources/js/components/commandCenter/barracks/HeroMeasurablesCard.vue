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
        <v-card>
            <v-container>
                <v-row :justify="'center'">
                    <v-col>
                        <v-row :align="'center'">
                            <v-card>
                                <v-btn
                                    fab
                                    small
                                    @click="increaseRaiseAmount"
                                >
                                    <v-icon dark>add</v-icon>
                                </v-btn>
                            </v-card>
                            <!--                                    <v-text-field-->
                            <!--                                        outlined-->
                            <!--                                        solo-->
                            <!--                                        type="number"-->
                            <!--                                        v-model="measurableRaiseAmount"-->
                            <!--                                    ></v-text-field>-->
                        </v-row>
                    </v-col>
                    <v-col>
                        <v-row no gutters>
                            <v-col class="py-1">
                                <v-btn
                                    fab
                                    small
                                    @click="increaseRaiseAmount"
                                >
                                    <v-icon dark>add</v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                        <v-row no gutters>
                            <v-col class="py-1">
                                <v-btn
                                    fab
                                    small
                                    @click="lowerRaiseAmount"
                                >
                                    <v-icon dark>remove</v-icon>
                                </v-btn>
                            </v-col>
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
        </v-card>
        <v-dialog
            v-model="measurableFocused"
            max-width="600"
        >
            <v-card>
                <v-card-title class="headline">{{measurableCardTitle}}</v-card-title>
                <v-card-actions>
                    <v-container fluid>
                        <v-row :justify="'center'">
                            <v-col>
                                <v-container>
                                    <v-row :align="'center'">
                                        <v-card>
                                            <v-btn
                                                fab
                                                small
                                                @click="increaseRaiseAmount"
                                            >
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-card>
                                        <!--                                    <v-text-field-->
                                        <!--                                        outlined-->
                                        <!--                                        solo-->
                                        <!--                                        type="number"-->
                                        <!--                                        v-model="measurableRaiseAmount"-->
                                        <!--                                    ></v-text-field>-->
                                    </v-row>
                                </v-container>
                            </v-col>
                            <v-col>
                                <v-row no gutters>
                                    <v-col class="py-1">
                                        <v-btn
                                            fab
                                            small
                                            @click="increaseRaiseAmount"
                                        >
                                            <v-icon dark>add</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-row no gutters>
                                    <v-col class="py-1">
                                        <v-btn
                                            fab
                                            small
                                            @click="lowerRaiseAmount"
                                        >
                                            <v-icon dark>remove</v-icon>
                                        </v-btn>
                                    </v-col>
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
            measurableCardTitle() {
                return this.measurableName + ': ' + this.focusedMeasurable.amount_raised;
            }
        }
    }
</script>

<style scoped>

</style>
