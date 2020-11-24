<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,1000,1000" style="display: block">

        <!-- Ally Front Line -->
        <path :d="describeArcPath(220, 0, 100, true)" :fill="'#298acf'" opacity="0.3" stroke="#333333"></path>
        <path :d="describeArcPath(220, 0, tweenedAllyHealthPercents.frontLine, true)" :fill="'#298acf'" stroke="#333333"></path>
        <!-- Ally Back Line -->
        <path :d="describeArcPath(350, 220, 100, true)" :fill="'#29b1cf'" opacity="0.3" stroke="#333333"></path>
        <path :d="describeArcPath(350, 220, tweenedAllyHealthPercents.backLine, true)" :fill="'#29b1cf'" stroke="#333333"></path>
        <!-- Ally High Ground -->
        <path :d="describeArcPath(450, 350, 100, true)" :fill="'#29cfc1'" opacity="0.3" stroke="#333333"></path>
        <path :d="describeArcPath(450, 350, tweenedAllyHealthPercents.highGround, true)" :fill="'#29cfc1'" stroke="#333333"></path>

        <!-- Enemy Front Line -->
        <path :d="describeArcPath(220, 0, 100, false)" :fill="'#e85c35'" opacity="0.3" stroke="#333333"></path>
        <path :d="describeArcPath(220, 0, tweenedEnemyHealthPercents.frontLine, false)" :fill="'#e85c35'" stroke="#333333"></path>
        <!-- Enemy Back Line -->
        <path :d="describeArcPath(350, 220, 100, false)" :fill="'#fc7e23'" opacity="0.3" stroke="#333333"></path>
        <path :d="describeArcPath(350, 220, tweenedEnemyHealthPercents.backLine, false)" :fill="'#fc7e23'" stroke="#333333"></path>
        <!-- Enemy High Ground -->
        <path :d="describeArcPath(450, 350, 100, false)" :fill="'#ffa500'" opacity="0.3" stroke="#333333"></path>
        <path :d="describeArcPath(450, 350, tweenedEnemyHealthPercents.highGround, false)" :fill="'#ffa500'" stroke="#333333"></path>

    </svg>
</template>

<script>
    import TWEEN from "@tweenjs/tween.js";
    export default {
        name: "CombatBattlefield",
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
        },
        methods: {
            describeArcPath(outerRadius, innerRadius, percent, allySide = true) {
                let xOrigin = allySide ? 480 : 520;
                let yOrigin = 500;
                let outerArcEnd = {
                    x: this.getXPosition(xOrigin, outerRadius, percent, allySide),
                    y: this.getYPosition(yOrigin, outerRadius, percent, allySide)
                };
                let innerArcStart = {
                    x: this.getXPosition(xOrigin, innerRadius, percent, allySide),
                    y: this.getYPosition(yOrigin, innerRadius, percent, allySide)
                };
                return [
                    "M", xOrigin, (yOrigin + innerRadius),
                    "L", xOrigin, (yOrigin + outerRadius),
                    "A", outerRadius, outerRadius, 0, 0, allySide ? 1 : 0, outerArcEnd.x, outerArcEnd.y,
                    "L", innerArcStart.x, innerArcStart.y,
                    "A", innerRadius, innerRadius, 0, 0, allySide ? 0 : 1, xOrigin, (yOrigin + innerRadius)
                ].join(" ");
            },
            getXPosition(xOrigin, radius, percent, allySide) {
                let radians = this.getRadians(percent, allySide);
                return xOrigin + (radius * Math.cos(radians));
            },
            getYPosition(yOrigin, radius, percent, allySide) {
                let radians = this.getRadians(percent, allySide);
                return yOrigin - (radius * Math.sin(radians));
            },
            getRadians(percent, allySide = true) {
                let offset = (100 - percent)/100 * 180;
                let degrees = allySide ? offset + 90 : 90 - offset;
                return degrees * (Math.PI/180);
            }
        },
    }
</script>

<style scoped>

</style>
