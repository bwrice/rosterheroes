<template>
    <v-sheet flat color="#576269" class="py-1 px-2 ma-1">
        <v-row no-gutters align="center">
            <v-col cols="4">
                <span class="subtitle-2 font-weight-bold">{{measurableName}}</span>
            </v-col>
            <v-col cols="6" class="px-2">
                <RelativeMeasurableBar :height="20" :measurable="measurable"></RelativeMeasurableBar>
            </v-col>
            <v-col cols="2">
                <v-row justify="center" align="center">
                    <v-btn @click="expanded = ! expanded"
                           fab
                           dark
                           color="rgba(0, 0, 0, .5)"
                           x-small>
                        <v-icon v-if="expanded">expand_less</v-icon>
                        <v-icon v-else>expand_more</v-icon>
                    </v-btn>
                </v-row>
            </v-col>
        </v-row>
        <template v-if="expanded">
            <v-row>
                <v-col cols="12">
                    <v-sheet
                        color="rgba(0, 0, 0, .3)"
                        class="pa-4"
                    >
                        <v-row no-gutters class="pt-3" justify="space-between" align="start">
                            <v-row class="flex-column" justify="end" align="center">
                                <v-btn
                                    small
                                    color="rgba(0, 0, 0, .3)"
                                    :disabled="increaseDisabled"
                                    @click="increaseRaiseAmount"
                                >
                                    <v-icon dark>add</v-icon>
                                </v-btn>
                                <v-btn
                                    small
                                    color="rgba(0, 0, 0, .3)"
                                    :disabled="decreaseDisabled"
                                    @click="decreaseRaiseAmount"
                                >
                                    <v-icon dark>remove</v-icon>
                                </v-btn>
                            </v-row>
                            <v-text-field
                                outlined
                                solo
                                type="number"
                                :rules="[raiseAmountRules.positive, raiseAmountRules.tooLarge]"
                                v-model="measurableRaiseAmount"
                                @update:error="updateRaiseInputErrors"
                            >
                            </v-text-field>
                        </v-row>
                        <v-row no-gutters align="center">
                            <v-col cols="5" class="pl-2 pb-2">
                                <v-row no-gutters class="flex-column" justify="center" align="start">
                                    <span class="caption">Available: {{availableExperience}}</span>
                                    <span class="caption">Cost: {{costToRaise}}</span>
                                </v-row>
                            </v-col>
                            <v-col cols="7">
                                <v-btn
                                    color="success"
                                    block
                                    :disabled="raiseMeasurableDisabled"
                                    @click="raiseMeasurable"
                                >
                                    Raise {{measurableName}}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-sheet>
                </v-col>
            </v-row>
        </template>
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
                expanded: false,
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
                } else if (! this.raiseInputHasErrors) {
                    this.costToRaise = await measurableApi.getCostToRaise(this.measurable.uuid, this.measurableRaiseAmount);
                } else {
                    this.costToRaise = "invalid input";
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
