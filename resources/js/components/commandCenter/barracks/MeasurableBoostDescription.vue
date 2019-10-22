<template>
    <span class="subtitle-2 font-weight-regular" style="color: rgba(255, 255, 255, .7)">
        {{description}}
    </span>
</template>

<script>
    import MeasurableBoost from "../../../models/MeasurableBoost";

    import {mapGetters} from 'vuex';

    export default {
        name: "MeasurableBoostDescription",
        props: {
            measurableBoost: {
                type: MeasurableBoost,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_measurableTypeByID'
            ]),
            description() {
                return '+' + this.measurableBoost.boostAmount + ' ' + this.measurableType.name;
            },
            measurableType() {
                return this._measurableTypeByID(this.measurableBoost.measurableTypeID);
            }
        }
    }
</script>

<style scoped>

</style>
