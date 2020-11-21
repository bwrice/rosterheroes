<template>
    <g>
        <BattlefieldCombatPosition
            v-for="(combatPosition, id) in _combatPositions"
            :key="id"
            :combat-position="combatPosition"
            :ally-side="allySide"
        ></BattlefieldCombatPosition>
    </g>
</template>

<script>
    import {mapGetters} from 'vuex';
    import BattlefieldCombatPosition from "./BattlefieldCombatPosition";
    export default {
        name: "BattlefieldSide",
        components: {BattlefieldCombatPosition},
        props: {
            allySide: {
                type: Boolean,
                required: true
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
