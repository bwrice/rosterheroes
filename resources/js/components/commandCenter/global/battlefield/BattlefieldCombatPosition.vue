<template>
    <g>
        <path :d="fullArcPath" :fill="color" opacity="0.3" stroke="#333333"></path>
        <path :d="healthArcPath" :fill="color" stroke="#333333"></path>
        <BattlefieldDamage
            v-for="(battlefieldEvent, id) in battleFieldEvents"
            :key="id"
            :battlefield-damage-event="battlefieldEvent"
        ></BattlefieldDamage>
    </g>
</template>

<script>
    import BattlefieldDamage from "./BattlefieldDamage";
    import * as arcHelpers from "../../../../helpers/battlefieldArcHelpers";

    export default {
        name: "BattlefieldCombatPosition",
        components: {BattlefieldDamage},
        props: {
            outerRadius: {
                type: Number,
                required: true
            },
            innerRadius: {
                type: Number,
                required: true
            },
            healthPercent: {
                type: Number,
                required: true
            },
            allySide: {
                type: Boolean,
                required: true
            },
            color: {
                type: String,
                required: true
            },
            damages: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                battleFieldEvents: []
            }
        },
        created() {
            let vm = this;
            this.battleFieldEvents = createBattlefieldEventsFromDamages(this.damages, vm);
        },
        watch: {
            damages(newDamages) {
                let vm = this;
                this.battleFieldEvents = createBattlefieldEventsFromDamages(newDamages, vm);
            }
        },
        computed: {
            fullArcPath() {
                return arcHelpers.buildArcPath(this.outerRadius, this.innerRadius, 100, this.allySide);
            },
            healthArcPath() {
                return arcHelpers.buildArcPath(this.outerRadius, this.innerRadius, this.healthPercent, this.allySide);
            }
        }
    }

    function createBattlefieldEventsFromDamages(damages, vm) {
        let innerRadius = vm.innerRadius;
        let thickness = vm.outerRadius - vm.innerRadius;
        let allySide = vm.allySide;
        let xOrigin = allySide ? 480 : 520;
        let yOrigin = 500;

        return damages.map(function (damage) {

            // Create a random radius and percent value to calculate x and y coords
            let radius = innerRadius + (thickness/10) + (Math.random() * (thickness/2));
            let percent = 20 + (Math.random() * 60);
            let xPosition = arcHelpers.getXPosition(xOrigin, radius, percent, allySide);
            let yPosition = arcHelpers.getYPosition(yOrigin, radius, percent, allySide);

            return {
                damage,
                xPosition,
                yPosition
            };
        });
    }
</script>

<style scoped>

</style>
