<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,1000,1000" style="display: block">
        <BattlefieldSide :ally-side="true" :combat-group="allyCombatGroup"></BattlefieldSide>
        <BattlefieldSide :ally-side="false" :combat-group="enemyCombatGroup"></BattlefieldSide>
    </svg>
</template>

<script>
    import BattlefieldSide from "./BattlefieldSide";
    import CombatGroup from "../../../../models/CombatGroup";
    export default {
        name: "CombatBattlefield",
        components: {BattlefieldSide},
        props: {
            allyCombatGroup: {
                type: CombatGroup,
                required: true
            },
            enemyCombatGroup: {
                type: CombatGroup,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
