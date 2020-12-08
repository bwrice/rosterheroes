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
            <BattlefieldDeath
                v-for="(battlefieldDeath, id) in _battlefieldDeaths"
                :battlefield-death="battlefieldDeath"
                :key="id"
            ></BattlefieldDeath>
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
    import BattlefieldDeath from "./BattlefieldDeath";

    export default {
        name: "CombatBattlefield",
        components: {
            BattlefieldDeath,
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
                '_battlefieldAttacks',
                '_battlefieldDeaths'
            ])
        }
    }
</script>

<style scoped>

</style>
