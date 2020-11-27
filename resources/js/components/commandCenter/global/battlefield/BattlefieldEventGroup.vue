<template>
    <g>
        <g>
            <BattlefieldBlock
                v-for="(battlefieldBlock, id) in battlefieldBlocks"
                :key="id"
                :battlefield-block="battlefieldBlock"
            >
            </BattlefieldBlock>
        </g>
        <g>
            <BattlefieldDamage
                v-for="(battlefieldDamage, id) in battlefieldDamages"
                :key="id"
                :battlefield-damage-event="battlefieldDamage"
                :color="allySide ? '#fc7e23' : '#29b1cf'"
            ></BattlefieldDamage>
        </g>
    </g>
</template>

<script>
    import BattlefieldDamage from "./BattlefieldDamage";
    import * as arcHelpers from "../../../../helpers/battlefieldArcHelpers";
    import BattlefieldBlock from "./BattlefieldBlock";

    export default {
        name: "BattlefieldEventGroup",
        components: {BattlefieldBlock, BattlefieldDamage},
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
            },
            blocks: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                battlefieldDamages: [],
                battlefieldBlocks: []
            }
        },
        created() {
            this.battlefieldDamages = this.createBattlefieldDamagesFromDamages(this.damages);
            this.battlefieldBlocks = this.createBattlefieldBlocksFromBlocks(this.blocks);
        },
        watch: {
            damages(newDamages) {
                this.battlefieldDamages = this.createBattlefieldDamagesFromDamages(newDamages);
            },
            blocks(newBlocks) {
                this.battlefieldBlocks = this.createBattlefieldBlocksFromBlocks(newBlocks);
            },
        },
        methods: {
            createBattlefieldDamagesFromDamages(damages) {

                let damageObjects = damages.map(function (damage) {
                    return {damage}
                });

                return this.mapToRandomPositionObjects(damageObjects);
            },
            createBattlefieldBlocksFromBlocks(blocks) {
                return this.mapToRandomPositionObjects(blocks);
            },
            mapToRandomPositionObjects(initialObjects) {
                let innerRadius = this.innerRadius;
                let thickness = this.outerRadius - this.innerRadius;
                let allySide = this.allySide;
                let xOrigin = allySide ? 480 : 520;
                let yOrigin = 500;
                return initialObjects.map(function (initialObject) {

                    // Create a random radius and percent value to calculate x and y coords
                    let radius = innerRadius + (thickness/4) + (Math.random() * (thickness/2));
                    let percent = 20 + (Math.random() * 60);
                    let xPosition = arcHelpers.getXPosition(xOrigin, radius, percent, allySide);
                    let yPosition = arcHelpers.getYPosition(yOrigin, radius, percent, allySide);

                    return {
                        xPosition,
                        yPosition,
                        ...initialObject
                    };
                });
            }


        }
    }
</script>

<style scoped>

</style>
