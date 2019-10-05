<template>
    <v-progress-linear
        :color="color"
        height="10"
        :value="progressBarValue"
    >
        <template v-slot="{ value }">
            <span class="caption font-weight-bold">{{ measurable.buffedAmount }}</span>
        </template>
    </v-progress-linear>
</template>

<script>
    import Measurable from "../../../models/Measurable";
    import {mapGetters} from 'vuex';

    export default {
        name: "RelativeMeasurableBar",
        props: {
            measurable: {
                type: Measurable,
                required: true,
            },
            color: {
                type: String,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_squadHighMeasurable'
            ]),
            progressBarValue() {
                let measurableTypeName = this.measurable.measurableType.name;
                let squadHighAmount = this._squadHighMeasurable(measurableTypeName);
                if (! squadHighAmount) {
                    return 0;
                }
                return Math.ceil((this.buffedAmount/squadHighAmount) * 100);
            },
            buffedAmount() {
                return this.measurable.buffedAmount;
            }
        }
    }
</script>

<style scoped>

</style>
