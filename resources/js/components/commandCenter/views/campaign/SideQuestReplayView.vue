<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,1000,1000" style="display: block">
                    <path :d="describeArcPath(450, 350, 100, true)" fill="#29cfc1" opacity="0.3" stroke="#333333"/>
                    <path :d="describeArcPath(450, 350, 80, true)" fill="#29cfc1" stroke="#333333"/>
                    <path :d="describeArcPath(350, 220, 100, true)" fill="#29b1cf" opacity="0.3" stroke="#333333"/>
                    <path :d="describeArcPath(350, 220, 45, true)" fill="#29b1cf" stroke="#333333"/>
                    <path :d="describeArcPath(220, 0, 100, true)" fill="#298acf" opacity="0.3" stroke="#333333"/>
                    <path :d="describeArcPath(220, 0, 25, true)" fill="#298acf" stroke="#333333"/>
                    <path :d="describeArcPath(450, 350, 100, false)" fill="#ffa500" opacity="0.3" stroke="#000000"/>
                    <path :d="describeArcPath(450, 350, 33, false)" fill="#ffa500" stroke="#000000"/>
                    <path :d="describeArcPath(350, 220, 100, false)" fill="#fc7e23" opacity="0.3" stroke="#000000"/>
                    <path :d="describeArcPath(350, 220, 80, false)" fill="#fc7e23" stroke="#000000"/>
                    <path :d="describeArcPath(220, 0, 100, false)" fill="#e85c35" opacity="0.3" stroke="#000000"/>
                    <path :d="describeArcPath(220, 0, 55, false)" fill="#e85c35" stroke="#000000"/>
                </svg>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">

            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "SideQuestReplayView",
        methods: {
            describeArcPath(outerRadius, innerRadius, percent, attackerSide = true) {
                let xOrigin = attackerSide ? 480 : 520;
                let yOrigin = 500;
                let outerArcEnd = {
                    x: this.getXPosition(xOrigin, outerRadius, percent, attackerSide),
                    y: this.getYPosition(yOrigin, outerRadius, percent, attackerSide)
                };
                let innerArcStart = {
                    x: this.getXPosition(xOrigin, innerRadius, percent, attackerSide),
                    y: this.getYPosition(yOrigin, innerRadius, percent, attackerSide)
                };
                return [
                    "M", xOrigin, (yOrigin + innerRadius),
                    "L", xOrigin, (yOrigin + outerRadius),
                    "A", outerRadius, outerRadius, 0, 0, attackerSide ? 1 : 0, outerArcEnd.x, outerArcEnd.y,
                    "L", innerArcStart.x, innerArcStart.y,
                    "A", innerRadius, innerRadius, 0, 0, attackerSide ? 0 : 1, xOrigin, (yOrigin + innerRadius)
                ].join(" ");
            },
            getXPosition(xOrigin, radius, percent, attackerSide) {
                let radians = this.getRadians(percent, attackerSide);
                return xOrigin + (radius * Math.cos(radians));
            },
            getYPosition(yOrigin, radius, percent, attackerSide) {
                let radians = this.getRadians(percent, attackerSide);
                return yOrigin - (radius * Math.sin(radians));
            },
            getRadians(percent, attackerSide = true) {
                let offset = (100 - percent)/100 * 180;
                let degrees = attackerSide ? offset + 90 : 90 - offset;
                return degrees * (Math.PI/180);
            }
        },
        computed: {
        }
    }
</script>

<style scoped>

</style>
