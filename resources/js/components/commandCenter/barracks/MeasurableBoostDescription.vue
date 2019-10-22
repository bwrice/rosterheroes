<template>
    <span class="subtitle-2 font-weight-regular" style="color: rgba(0, 0, 0, .85)">
        {{description}}
    </span>
</template>

<script>
    import MeasurableBoost from "../../../models/MeasurableBoost";

    export default {
        name: "MeasurableBoostDescription",
        props: {
            measurableBoost: {
                type: MeasurableBoost,
                required: true
            }
        },
        computed: {
            description() {
                return '+' + this.measurableBoost.boostAmount;
            }
        }
    }
</script>

<style scoped>

</style>
