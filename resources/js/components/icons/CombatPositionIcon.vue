<template>
    <SvgIconSheet :elevation="elevation" :tool-tip-message="toolTipMessage">
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
            },
            toolTip: {
                type: Boolean,
                default: true
            },
            withPrefix: {
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
            },
            toolTipMessage() {
                let message = "";
                if (! this.toolTip) {
                    return message;
                }
                if (this.withPrefix) {

                    message += this.attackerMode ? 'Attacker ': 'Target ';
                }
                return message += this.combatPosition.name;
            }
        }
    }
</script>

<style scoped>

</style>

