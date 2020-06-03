<template>
    <SvgIconSheet :tool-tip-message="toolTipMessage">
        <component :is="targetPriorityComponent"></component>
    </SvgIconSheet>
</template>

<script>

    import {mapGetters} from 'vuex';
    import SvgIconSheet from "../../commandCenter/global/SvgIconSheet";
    import AnyTargetIcon from "./AnyTargetIcon";

    export default {
        name: "TargetPriorityIcon",
        components: {SvgIconSheet, AnyTargetIcon},
        props: {
            targetPriorityId: {
                type: Number,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_targetPriorityByID'
            ]),
            targetPriority() {
                return this._targetPriorityByID(this.targetPriorityId);
            },
            targetPriorityComponent() {
                if (this.targetPriority.name === 'Any') {
                    return 'AnyTargetIcon';
                }
            },
            toolTipMessage() {
                return this.targetPriority.name + ' Target';
            }
        }
    }
</script>

<style scoped>

</style>
