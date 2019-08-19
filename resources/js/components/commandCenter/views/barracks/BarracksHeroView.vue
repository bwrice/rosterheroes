<template>
    <v-col cols="12">
        <v-row no-gutters>
            <v-col cols="12">
                <v-card class="my-2">
                    <v-card-title>{{ _barracksFocusedHero.name }}</v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col cols="12">
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
                    </v-card>
                    <v-card>
                        <v-card-title>Qualities</v-card-title>
                    </v-card>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog
            v-model="measurableFocused"
            max-width="600"
        >
            <v-card>
                <v-card-title class="headline">{{}}</v-card-title>
                <v-card-actions>
                        <v-row>
                            <v-col cols="7">
                                <v-btn
                                    icon
                                    @click="lowerRaiseAmount"
                                >
                                    <v-icon dark>remove</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    @click="increaseRaiseAmount"
                                >
                                    <v-icon dark>add</v-icon>
                                </v-btn>

                                <v-text-field
                                    type="number"
                                    v-model="measurableRaiseAmount"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="5">
                                <v-btn
                                    color="primary"
                                    block
                                >
                                    Raise {{focusedMeasurable.measurable_type.name.toUpperCase()}}
                                </v-btn>
                            </v-col>
                        </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-col>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import MeasurablePanel from "../../barracks/MeasurablePanel";

    export default {
        name: "BarracksHeroView",
        components: {MeasurablePanel},

        mounted() {
            this.setBarracksFocusedHeroBySlug(this.$route.params.heroSlug);
        },

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

        watch: {
            $route (to) {
                if (to.params.heroSlug && to.params.heroSlug !== this._barracksFocusedHero.slug) {
                    this.setBarracksFocusedHeroBySlug(to.params.heroSlug);
                }
            }
        },

        methods: {
            ...mapActions([
                'setBarracksFocusedHeroBySlug'
            ]),
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
            ...mapGetters([
                '_barracksHeroes',
                '_barracksFocusedHero'
            ]),
            attributes() {
                return this._barracksFocusedHero.measurables.filter(function (measurable) {
                    return measurable.measurable_type.group === 'attribute';
                })
            },
            measurableName() {
                return this.focusedMeasurable.measurable_type.name.toUpperCase();
            },
            measurableCardTitle() {
                return this.measurableName() + ': ' + this.focusedMeasurable.amount_raised;
            }
        }
    }
</script>

<style scoped>

</style>
