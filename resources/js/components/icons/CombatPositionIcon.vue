<template>
    <SvgIconSheet :elevation="elevation">
        <component :is="combatPositionTypeComponent" v-bind="properties"></component>
    </SvgIconSheet>
</template>

<script>
    import SvgIconSheet from "../commandCenter/global/SvgIconSheet";
    import AttackerIcon from "./combatPositions/AttackerIcon";
    import DefenderIcon from "./combatPositions/DefenderIcon";
    import {mapGetters} from 'vuex';

    export default {
        name: "CombatPositionIcon",
        components: {SvgIconSheet, AttackerIcon, DefenderIcon},
        props: {
            combatPositionId: {
                type: Number,
                required: true
            },
            attackerMode: {
                type: Boolean,
                default: true
            },
            elevation: {
                type: Number,
                default: 0
            }
        },
        computed: {
            ...mapGetters([
                '_combatPositionByID'
            ]),
            combatPosition() {
                return this._combatPositionByID(this.combatPositionId);
            },
            combatPositionTypeComponent() {
                if (this.attackerMode) {
                    return 'AttackerIcon';
                }
                return 'DefenderIcon';
            },
            properties() {
                return {
                    'combatPositionName' : this.combatPosition.name
                }
            }
        }
    }
</script>

<style scoped>

</style>

