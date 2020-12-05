<template>
    <g>
        <g>
            <BattlefieldBlock
                v-for="(blockEvent, id) in battlefieldAttack.battlefieldBlocks"
                :key="id"
                :battlefield-block-event="blockEvent"
                :source-x="sourceX"
                :source-y="sourceY"
            ></BattlefieldBlock>
        </g>
        <g>
            <BattlefieldDamage
                v-for="(damageEvent, id) in battlefieldAttack.battlefieldDamages"
                :key="id"
                :battlefield-damage-event="damageEvent"
                :source-x="sourceX"
                :source-y="sourceY"
            ></BattlefieldDamage>
        </g>
    </g>
</template>

<script>
    import BattlefieldAttackEvent from "../../../../models/battlefield/BattlefieldAttackEvent";
    import BattlefieldDamage from "./BattlefieldDamage";
    import BattlefieldBlock from "./BattlefieldBlock";

    export default {
        name: "BattlefieldAttackAnimation",
        components: {BattlefieldBlock, BattlefieldDamage},
        props: {
            battlefieldAttack: {
                type: BattlefieldAttackEvent,
                required: true
            }
        },
        data() {
            return {
                sourceX: 0,
                sourceY: 0
            }
        },
        created() {
            this.setRandomSourceCoords();
        },
        watch: {
            battlefieldAttack() {
                this.setRandomSourceCoords();
            }
        },
        methods: {
            setRandomSourceCoords() {
                let coords = this.battlefieldAttack.getRandomCoords();
                this.sourceX = coords.x;
                this.sourceY = coords.y;
            }
        }
    }
</script>

<style scoped>

</style>
