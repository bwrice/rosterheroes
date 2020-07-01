<template>
    <v-sheet
        class="mx-2 mb-2 rounded"
        color="#4c6973"
    >
        <v-row no-gutters>
            <v-col cols="12">
                <v-row no-gutters align="center" class="px-2 py-3">
                    <v-col cols="5" sm="4">
                        <span :class="qualityNameClasses" style="color: rgba(255, 255, 255, .8)">{{qualityType.name.toUpperCase()}}</span>
                    </v-col>
                    <v-col cols="7" sm="8">
                        <GradientBar
                            :percent="progressBarValue"
                            :height="32"
                        >
                            <span class="caption font-weight-bold">+{{ bonusPercent }}%</span>
                        </GradientBar>
                    </v-col>
                </v-row>
            </v-col>
            <v-col
                cols="12"
                sm="6"
                v-for="(statType, id) in statTypes"
                :key="id"
            >
                <v-sheet
                    color="rgba(0,0,0,.3)"
                    class="mx-2 mb-1 rounded-sm"
                >
                    <v-row no-gutters justify="space-between" class="pa-2">
                        <span>{{statType.simpleName}}</span>
                        <span :class="statType.pointsPer < 0 ? 'error--text' : 'success--text'">{{statType.pointsPer}}</span>
                    </v-row>
                </v-sheet>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import MeasurableType from "../../../models/MeasurableType";
    import GradientBar from "../global/GradientBar";

    export default {
        name: "QualityStatBonusGroup",
        components: {GradientBar},
        props: {
            qualityType: {
                type: MeasurableType,
                required: true
            },
            statTypes: {
                type: Array,
                required: true
            },
            percentModifier: {
                type: Number,
                required: true
            }
        },
        computed: {
            progressBarValue() {
                return Math.ceil(this.bonusPercent * (1/2)); // range of (0 - 200) over 100 base value
            },
            bonusPercent() {
                return this.percentModifier - 100;
            },
            qualityNameClasses() {
                let textSizeClass = '';
                let textWeightClass = '';
                let paddingClass = '';
                if (this.$vuetify.breakpoint.name === 'xs') {

                    textSizeClass = 'subtitle-1';
                    textWeightClass = 'font-weight-regular';
                    paddingClass = 'pl-2';
                } else {

                    textSizeClass = 'title';
                    textWeightClass = 'font-weight-bold';
                    paddingClass = 'pl-4';
                }

                return [textSizeClass, textWeightClass, paddingClass];
            },
            pointsPerClasses() {
                let textColorClass = 'text--primary';
                if (this.percentModifier < 0) {
                    return textColorClass = 'text--error';
                }
                return [textColorClass];
            }
        }
    }
</script>

<style scoped>

</style>
