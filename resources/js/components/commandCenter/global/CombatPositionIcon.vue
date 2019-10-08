<template>
    <SvgIconSheet :svg="svg">
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
                return this.combatPosition.defenderSVG;
            }
        }
    }
</script>

<style scoped>

</style>

