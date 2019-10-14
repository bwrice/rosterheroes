<template>
    <SvgIconSheet :svg="svg" :clickable="clickable" @iconClicked="emitClicked">
    </SvgIconSheet>
</template>

<script>
    import SvgIconSheet from "./SvgIconSheet";
    import {mapGetters} from 'vuex';

    export default {
        name: "CombatPositionIcon",
        components: {SvgIconSheet},
        props: {
            combatPositionId: {
                type: Number,
                required: true
            },
            attackerMode: {
                type: Boolean,
                default: true
            },
            clickable: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            emitClicked() {
                this.$emit('combatPositionClicked');
            }
        },
        computed: {
            ...mapGetters([
                '_combatPositionByID'
            ]),
            combatPosition() {
                return this._combatPositionByID(this.combatPositionId);
            },
            svg() {
                if (this.attackerMode) {
                    return this.combatPosition.attackerSVG;
                }
                return this.combatPosition.targetSVG;
            }
        }
    }
</script>

<style scoped>

</style>

