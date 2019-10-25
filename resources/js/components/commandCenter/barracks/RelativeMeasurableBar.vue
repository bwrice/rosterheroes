<template>
    <v-progress-linear
        :color="color"
        :height="height"
        :value="progressBarValue"
    >
        <template v-slot="{ value }">
            <span class="caption font-weight-bold">{{ currentAmount }}</span>
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
                default: 'primary'
            },
            height: {
                type: Number,
                default: 10
            },
        },
        computed: {
            ...mapGetters([
                '_squadHighMeasurable',
                '_measurableTypeByID'
            ]),
            progressBarValue() {
                let squadHighAmount = this._squadHighMeasurable(this.measurable.measurableTypeID);
                if (! squadHighAmount) {
                    return 0;
                }
                return Math.ceil((this.currentAmount/squadHighAmount) * 100);
            },
            currentAmount() {
                return this.measurable.currentAmount;
            }
        }
    }
</script>

<style scoped>

</style>
