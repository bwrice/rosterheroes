<template>
    <g>
        <BattlefieldDamage
            v-for="(battlefieldDamage, id) in battlefieldDamages"
            :key="id"
            :battlefield-damage-event="battlefieldDamage"
            :color="allySide ? '#fc7e23' : '#29b1cf'"
        ></BattlefieldDamage>
    </g>
</template>

<script>
    import BattlefieldDamage from "./BattlefieldDamage";
    import * as arcHelpers from "../../../../helpers/battlefieldArcHelpers";

    export default {
        name: "BattlefieldEventGroup",
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
            allySide: {
                type: Boolean,
                required: true
            },
            damages: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                battlefieldDamages: []
            }
        },
        created() {
            let vm = this;
            this.battlefieldDamages = createBattlefieldDamagesFromDamages(this.damages, vm);
        },
        watch: {
            damages(newDamages) {
                let vm = this;
                this.battlefieldDamages = createBattlefieldDamagesFromDamages(newDamages, vm);
            }
        }
    }

    function createBattlefieldDamagesFromDamages(damages, vm) {
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
