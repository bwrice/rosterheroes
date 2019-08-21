<template>
    <v-sheet flat color="#706856" class="pa-1 ma-1">
        <v-row no-gutters :align="'center'" justify="space-between">
            <p>{{measurableName}}</p>
            <v-btn @click="measurableFocused = true">+</v-btn>
        </v-row>
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
                                    :rules="[raiseAmountRules.positive, raiseAmountRules.tooLarge]"
                                    v-model="measurableRaiseAmount"
                                    @update:error="updateRaiseInputErrors"
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-row :justify="'center'">
                                    <v-btn
                                        fab
                                        small
                                        :disabled="increaseDisabled"
                                        @click="increaseRaiseAmount"
                                    >
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                    <v-btn
                                        fab
                                        small
                                        :disabled="decreaseDisabled"
                                        @click="decreaseRaiseAmount"
                                    >
                                        <v-icon dark>remove</v-icon>
                                    </v-btn>
                                </v-row>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-row :justify="'center'">
                                    Available: {{availableExperience}}
                                </v-row>
                            </v-col>
                            <v-col cols="6">
                                <v-row :justify="'center'">
                                    Cost: {{costToRaise}}
                                </v-row>
                            </v-col>
                        </v-row>
                        <v-row no gutters>
                            <v-col cols="4">
                                <v-btn
                                    color="error"
                                    block
                                    @click="measurableFocused = false"
                                >
                                    Cancel
                                </v-btn>
                            </v-col>
                            <v-col cols="8">
                                <v-btn
                                    color="primary"
                                    :disabled="raiseMeasurableDisabled"
                                    block
                                >
                                    Raise {{measurableName}}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-sheet>
</template>

<script>

    import * as measurableApi from '../../../api/measurableApi';

    import {barracksHeroMixin} from "../../../mixins/barracksHeroMixin";

    export default {
        name: "MeasurablePanel",
        props: {
            measurable: Object,
            required: true
        },
        mixins: [
            barracksHeroMixin
        ],

        mounted() {
            this.costToRaise = this.measurable.cost_to_raise;
        },
        created() {
            this.debounceSetCostToRaiseAmount = _.debounce(async function() {
                this.setCostToRaiseAmount()
            }, 500);
        },

        data() {
            return {
                measurableFocused: false,
                measurableRaiseAmount: 1,
                costToRaise: 0,
                raiseAmountRules: {
                    positive: amount => amount > 0 || 'must be a positive number',
                    tooLarge: amount => amount <= 100 || 'amount is too large'
                },
                raiseInputHasErrors: false,
                calculatingCost: false,
            }
        },
        watch: {
            measurableRaiseAmount: function (newAmount, oldAmount) {
                this.costToRaise = 'Calculating...';
                this.calculatingCost = true;
                this.debounceSetCostToRaiseAmount();
            }
        },

        methods: {
            decreaseRaiseAmount() {
                this.measurableRaiseAmount--;
            },
            increaseRaiseAmount() {
                this.measurableRaiseAmount++;
            },
            async setCostToRaiseAmount() {
                if (this.measurableRaiseAmount <= 1) {
                    this.costToRaise = this.measurable.cost_to_raise;
                } else {
                    this.costToRaise = await measurableApi.getCostToRaise(this.measurable.uuid, this.measurableRaiseAmount);
                }
                this.calculatingCost = false;
            },
            updateRaiseInputErrors(hasErrors) {
                this.raiseInputHasErrors = hasErrors;
            }
        },
        computed: {
            measurableName() {
                return this.measurable.measurable_type.name.toUpperCase();
            },
            potentialAmount() {
                let potential = this.measurable.current_amount;
                if (this.raiseAmount) {
                    potential += this.raiseAmount;
                }
                return potential;
            },
            raiseAmount() {
                return parseInt(this.measurableRaiseAmount);
            },
            increaseDisabled() {
                return this.measurableRaiseAmount >= 100;
            },
            decreaseDisabled() {
                return this.measurableRaiseAmount <= 1;
            },
            raiseMeasurableDisabled() {
                if (this.raiseInputHasErrors || this.calculatingCost) {
                    return true;
                } else if( this.costToRaise > this.availableExperience ) {
                    return true;
                }
                return false;
            }
        }
    }
</script>

<style scoped>

</style>
