<template>
    <g>
        <path :d="describeArcPath(combatPosition.outerRadius, combatPosition.innerRadius, 100, allySide)" :fill="fillColor" opacity="0.3" stroke="#333333"></path>
        <path :d="describeArcPath(combatPosition.outerRadius, combatPosition.innerRadius, 80, allySide)" :fill="fillColor" stroke="#333333"></path>
    </g>
</template>

<script>
    import CombatPosition from "../../../../models/CombatPosition";

    export default {
        name: "BattlefieldCombatPosition",
        props: {
            allySide: {
                type: Boolean,
                required: true
            },
            combatPosition: {
                type: CombatPosition,
                required: true
            }
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
        computed: {
            fillColor() {
                if (this.allySide) {
                    return this.combatPosition.allyColor;
                }
                return this.combatPosition.enemyColor;
            }
        }
    }
</script>

<style scoped>

</style>
