<template>
    <g>
        <BattlefieldDamage
            v-for="(damageEvent, id) in battlefieldAttack.battlefieldDamages"
            :key="id"
            :battlefield-damage-event="damageEvent"
            :source-x="sourceX"
            :source-y="sourceY"
        ></BattlefieldDamage>
    </g>
</template>

<script>
    import BattlefieldAttack from "../../../../models/battlefield/BattlefieldAttack";
    import BattlefieldDamage from "./BattlefieldDamage";

    export default {
        name: "BattlefieldAttackAnimation",
        components: {BattlefieldDamage},
        props: {
            battlefieldAttack: {
                type: BattlefieldAttack,
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
