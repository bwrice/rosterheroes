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
            <AllyEventGroup
                v-for="combatPositionName in combatPositionNames"
                :combat-position-name="combatPositionName"
                :key="combatPositionName"
            ></AllyEventGroup>
        </g>
        <g>
            <EnemyEventGroup
                v-for="combatPositionName in combatPositionNames"
                :combat-position-name="combatPositionName"
                :key="combatPositionName"
            ></EnemyEventGroup>
        </g>
    </svg>
</template>

<script>

    import AllyEventGroup from "./AllyEventGroup";
    import EnemyEventGroup from "./EnemyEventGroup";
    import AllyBattlefieldArc from "./AllyBattlefieldArc";
    import EnemyBattlefieldArc from "./EnemyBattlefieldArc";

    export default {
        name: "CombatBattlefield",
        components: {
            EnemyBattlefieldArc,
            AllyBattlefieldArc,
            EnemyEventGroup,
            AllyEventGroup
        },
        data() {
            return {
                combatPositionNames: [
                    'front-line',
                    'back-line',
                    'high-ground'
                ]
            }
        }
        // watch: {
        //     allyHealthPercents: function(newValue) {
        //         function animate () {
        //             if (TWEEN.update()) {
        //                 requestAnimationFrame(animate)
        //             }
        //         }
        //         new TWEEN.Tween(this.tweenedAllyHealthPercents)
        //             .to(newValue, 1000)
        //             .easing(TWEEN.Easing.Quadratic.Out)
        //             .start();
        //
        //         animate();
        //     },
        //     enemyHealthPercents: function(newValue) {
        //         function animate () {
        //             if (TWEEN.update()) {
        //                 requestAnimationFrame(animate)
        //             }
        //         }
        //         new TWEEN.Tween(this.tweenedEnemyHealthPercents)
        //             .to(newValue, 1000)
        //             .easing(TWEEN.Easing.Quadratic.Out)
        //             .start();
        //
        //         animate();
        //     },
        // }
    }
</script>

<style scoped>

</style>
