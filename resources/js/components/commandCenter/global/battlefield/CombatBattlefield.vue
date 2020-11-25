<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,1000,1000" style="display: block">

        <!-- Ally Front Line -->
        <BattlefieldCombatPosition
            :outer-radius="220"
            :inner-radius="0"
            :health-percent="tweenedAllyHealthPercents.frontLine"
            :ally-side="true"
            :color="'#298acf'"
        ></BattlefieldCombatPosition>

        <!-- Ally Back Line -->
        <BattlefieldCombatPosition
            :outer-radius="350"
            :inner-radius="220"
            :health-percent="tweenedAllyHealthPercents.backLine"
            :ally-side="true"
            :color="'#29b1cf'"
        ></BattlefieldCombatPosition>

        <!-- Ally High Ground -->
        <BattlefieldCombatPosition
            :outer-radius="450"
            :inner-radius="350"
            :health-percent="tweenedAllyHealthPercents.highGround"
            :ally-side="true"
            :color="'#29cfc1'"
        ></BattlefieldCombatPosition>

        <!-- Enemy Front Line -->
        <BattlefieldCombatPosition
            :outer-radius="220"
            :inner-radius="0"
            :health-percent="tweenedEnemyHealthPercents.frontLine"
            :ally-side="false"
            :color="'#e85c35'"
        ></BattlefieldCombatPosition>

        <!-- Enemy Back Line -->
        <BattlefieldCombatPosition
            :outer-radius="350"
            :inner-radius="220"
            :health-percent="tweenedEnemyHealthPercents.backLine"
            :ally-side="false"
            :color="'#fc7e23'"
        ></BattlefieldCombatPosition>

        <!-- Enemy High Ground -->
        <BattlefieldCombatPosition
            :outer-radius="450"
            :inner-radius="350"
            :health-percent="tweenedEnemyHealthPercents.highGround"
            :ally-side="false"
            :color="'#ffa500'"
        ></BattlefieldCombatPosition>

    </svg>
</template>

<script>
    import TWEEN from "@tweenjs/tween.js";
    import BattlefieldCombatPosition from "./BattlefieldCombatPosition";
    export default {
        name: "CombatBattlefield",
        components: {BattlefieldCombatPosition},
        props: {
            allyHealthPercents: {
                type: Object,
                required: true
            },
            enemyHealthPercents: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                tweenedAllyHealthPercents: {},
                tweenedEnemyHealthPercents: {}
            }
        },
        created() {
            this.tweenedAllyHealthPercents = _.cloneDeep(this.allyHealthPercents);
            this.tweenedEnemyHealthPercents = _.cloneDeep(this.enemyHealthPercents);
        },
        watch: {
            allyHealthPercents: function(newValue) {
                function animate () {
                    if (TWEEN.update()) {
                        requestAnimationFrame(animate)
                    }
                }
                new TWEEN.Tween(this.tweenedAllyHealthPercents)
                    .to(newValue, 1000)
                    .easing(TWEEN.Easing.Quadratic.Out)
                    .start();

                animate();
            },
            enemyHealthPercents: function(newValue) {
                function animate () {
                    if (TWEEN.update()) {
                        requestAnimationFrame(animate)
                    }
                }
                new TWEEN.Tween(this.tweenedEnemyHealthPercents)
                    .to(newValue, 1000)
                    .easing(TWEEN.Easing.Quadratic.Out)
                    .start();

                animate();
            },
        }
    }
</script>

<style scoped>

</style>
