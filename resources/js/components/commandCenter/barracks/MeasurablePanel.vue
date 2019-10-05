<template>
    <v-sheet flat color="#706856" class="py-1 px-2 ma-1">
        <v-row no-gutters align="center">
            <v-col cols="4">
                <span class="subtitle-2 font-weight-bold">{{measurableName}}</span>
            </v-col>
            <v-col cols="6" class="px-2">
                <RelativeMeasurableBar :height="20" :measurable="measurable"></RelativeMeasurableBar>
            </v-col>
            <v-col cols="2">
                <v-btn small @click="measurableFocused = true">+</v-btn>
            </v-col>
        </v-row>
        <v-dialog
            v-model="measurableFocused"
            max-width="600"
        >
            <v-card>
                <v-card-title class="headline">{{measurableName}}: {{measurable.buffedAmount}}</v-card-title>
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
                                    @click="raiseMeasurable"
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

    import {mapActions} from 'vuex';
    import Measurable from "../../../models/Measurable";
    import RelativeMeasurableBar from "./RelativeMeasurableBar";

    export default {
        name: "MeasurablePanel",
        components: {RelativeMeasurableBar},
        props: {
            measurable: Measurable,
            required: true
        },
        mixins: [
            barracksHeroMixin
        ],

        mounted() {
            this.costToRaise = this.measurable.costToRaise;
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
                pendingMeasurableRaise: false,
            }
        },
        watch: {
            measurableRaiseAmount: function (newAmount, oldAmount) {
                this.costToRaise = 'Calculating...';
                this.pendingMeasurableRaise = true;
                this.debounceSetCostToRaiseAmount();
            }
        },

        methods: {
            ...mapActions([
                'raiseHeroMeasurable'
            ]),
            decreaseRaiseAmount() {
                this.measurableRaiseAmount--;
            },
            increaseRaiseAmount() {
                this.measurableRaiseAmount++;
            },
            async setCostToRaiseAmount() {
                if (this.measurableRaiseAmount <= 1) {
                    this.costToRaise = this.measurable.costToRaise;
                } else {
                    this.costToRaise = await measurableApi.getCostToRaise(this.measurable.uuid, this.measurableRaiseAmount);
                }
                this.pendingMeasurableRaise = false;
            },
            updateRaiseInputErrors(hasErrors) {
                this.raiseInputHasErrors = hasErrors;
            },
            async raiseMeasurable() {
                this.pendingMeasurableRaise = true;

                await this.raiseHeroMeasurable({
                    heroSlug: this.$route.params.heroSlug,
                    measurableUuid: this.measurable.uuid,
                    raiseAmount: this.measurableRaiseAmount
                });

                this.measurableRaiseAmount = 1;
                await this.setCostToRaiseAmount();
                this.pendingMeasurableRaise = false;
            }
        },
        computed: {
            measurableName() {
                return this.measurable.measurableType.name.toUpperCase();
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
                if (this.raiseInputHasErrors || this.pendingMeasurableRaise) {
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
