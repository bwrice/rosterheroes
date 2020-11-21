<template>
    <g>
        <BattlefieldCombatPosition
            v-for="(combatPosition, id) in _combatPositions"
            :key="id"
            :combat-position="combatPosition"
            :ally-side="allySide"
            :combatants="filteredCombatants(combatPosition.id)"
        ></BattlefieldCombatPosition>
    </g>
</template>

<script>
    import {mapGetters} from 'vuex';
    import BattlefieldCombatPosition from "./BattlefieldCombatPosition";
    import CombatGroup from "../../../../models/CombatGroup";
    export default {
        name: "BattlefieldSide",
        components: {BattlefieldCombatPosition},
        props: {
            allySide: {
                type: Boolean,
                required: true
            },
            combatGroup: {
                type: CombatGroup,
                required: true
            }
        },
        methods: {
            filteredCombatants(combatPositionID) {
                return this.combatGroup.combatants.filter(combatant => combatant.combatPositionID === combatPositionID);
            }
        },
        computed: {
            ...mapGetters([
                '_combatPositions'
            ])
        }
    }
</script>

<style scoped>

</style>
