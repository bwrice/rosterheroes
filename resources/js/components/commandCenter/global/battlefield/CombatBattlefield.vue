<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,1000,1000" style="display: block">
        <!-- ally side -->
        <BattlefieldCombatPosition
            v-for="(combatPosition, id) in _combatPositions"
            :key="id"
            :combat-position="combatPosition"
            :ally-side="true"
        ></BattlefieldCombatPosition>
        <!-- enemy side -->
        <BattlefieldCombatPosition
            v-for="(combatPosition, id) in _combatPositions"
            :key="id"
            :combat-position="combatPosition"
            :ally-side="false"
        ></BattlefieldCombatPosition>
    </svg>
</template>

<script>
    import {mapGetters} from 'vuex';
    import BattlefieldCombatPosition from "./BattlefieldCombatPosition";
    export default {
        name: "CombatBattlefield",
        components: {BattlefieldCombatPosition},
        computed: {
            ...mapGetters([
                '_combatPositions'
            ])
        }
    }
</script>

<style scoped>

</style>
