<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,1000,1000" style="display: block">
        <g>
            <AllyBattlefieldArc
                v-for="combatPositionName in combatPositionNames"
                :combat-position-name="combatPositionName"
                :key="combatPositionName"
            ></AllyBattlefieldArc>
        </g>
        <g>
            <EnemyBattlefieldArc
                v-for="combatPositionName in combatPositionNames"
                :combat-position-name="combatPositionName"
                :key="combatPositionName"
            ></EnemyBattlefieldArc>
        </g>
        <g>
            <BattlefieldAttackAnimation
                v-for="(battlefieldAttack, id) in _battlefieldAttacks"
                :battlefield-attack="battlefieldAttack"
                :key="id"
            ></BattlefieldAttackAnimation>
        </g>
    </svg>
</template>

<script>

    import AllyBattlefieldArc from "./AllyBattlefieldArc";
    import EnemyBattlefieldArc from "./EnemyBattlefieldArc";
    import BattlefieldAttackAnimation from "./BattlefieldAttackAnimation";
    import {mapGetters} from 'vuex';

    export default {
        name: "CombatBattlefield",
        components: {
            BattlefieldAttackAnimation,
            EnemyBattlefieldArc,
            AllyBattlefieldArc
        },
        data() {
            return {
                combatPositionNames: [
                    'front-line',
                    'back-line',
                    'high-ground'
                ]
            }
        },
        computed: {
            ...mapGetters([
                '_battlefieldAttacks'
            ])
        }
    }
</script>

<style scoped>

</style>
