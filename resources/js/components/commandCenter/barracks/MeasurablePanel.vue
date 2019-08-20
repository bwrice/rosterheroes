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
                                    v-model="measurableRaiseAmount"
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
    export default {
        name: "MeasurablePanel",
        props: {
            measurable: Object,
            required: true
        },

        data() {
            return {
                measurableFocused: false,
                measurableRaiseAmount: 1,
                costToRaise: 0
            }
        },
        methods: {
            decreaseRaiseAmount() {
                this.measurableRaiseAmount--;
            },
            increaseRaiseAmount() {
                this.measurableRaiseAmount++;
            },
            getCostToRaiseAmount() {

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
        }
    }
</script>

<style scoped>

</style>
