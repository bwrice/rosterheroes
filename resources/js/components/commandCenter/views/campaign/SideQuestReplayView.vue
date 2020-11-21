<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <CombatBattlefield></CombatBattlefield>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">

            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import CombatBattlefield from "../../global/battlefield/CombatBattlefield";
    export default {
        name: "SideQuestReplayView",
        components: {CombatBattlefield},
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
            ...mapGetters([
                '_sideQuestCombatSquad',
                '_sideQuestEnemyGroup'
            ])
        }
    }
</script>

<style scoped>

</style>
