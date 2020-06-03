<template>
    <SvgIconSheet :tool-tip-message="toolTipMessage">
        <component :is="damageTypeComponent" v-bind="properties"></component>
    </SvgIconSheet>
</template>

<script>
    import SvgIconSheet from "../../commandCenter/global/SvgIconSheet";
    import FixedTargetIcon from "./FixedTargetIcon";
    import AreaOfEffect from "./AreaOfEffect";

    import {mapGetters} from 'vuex';
    export default {
        name: "DamageTypeIcon",
        components: {SvgIconSheet, FixedTargetIcon, AreaOfEffect},
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
                return this._damageTypeByID(this.damageTypeId);
            },
            damageTypeComponent() {
                if (this.damageType.name === 'Fixed Target') {
                    return 'FixedTargetIcon';
                }
                if (this.damageType.name === 'Area of Effect') {
                    return 'AreaOfEffect';
                }
            },
            properties() {
                return {
                    'targetsCount': this.targetsCount
                }
            },
            toolTipMessage() {
                let message = this.damageType.name;
                if (this.targetsCount) {
                    message += ' (' + this.targetsCount + ')';
                }
                return message;
            }
        }
    }
</script>

<style scoped>

</style>
