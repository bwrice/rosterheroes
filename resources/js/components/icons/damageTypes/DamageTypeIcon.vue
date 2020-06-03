<template>
    <SvgIconSheet>
        <component :is="damageTypeComponent" v-bind="properties"></component>
    </SvgIconSheet>
</template>

<script>
    import SvgIconSheet from "../../commandCenter/global/SvgIconSheet";
    import FixedTargetIcon from "./FixedTargetIcon";

    import {mapGetters} from 'vuex';
    export default {
        name: "DamageTypeIcon",
        components: {SvgIconSheet, FixedTargetIcon},
        props: {
            damageTypeId: {
                type: Number,
                required: true
            },
            targetsCount: {
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_damageTypeByID'
            ]),
            damageType() {
                return this._damageTypeByID(this.damageTypeID);
            },
            damageTypeComponent() {
                return 'FixedTargetIcon'
            },
            properties() {
                return {
                    'targetsCount': this.targetsCount
                }
            }
        }
    }
</script>

<style scoped>

</style>
