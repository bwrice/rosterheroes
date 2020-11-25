<template>
    <g>
        <circle
            color="#fff"
            opacity=".7"
            :cx="battlefieldEvent.xPosition"
            :cy="battlefieldEvent.yPosition"
            :r="battlefieldEvent.magnitude"
        ></circle>
    </g>
</template>

<script>
    import BattlefieldEvent from "../../../../models/BattlefieldEvent";

    export default {
        name: "BattlefieldDamage",
        props: {
            battlefieldEvent: {
                type: BattlefieldEvent,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
