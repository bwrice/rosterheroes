<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,1000,1000" style="display: block">
        <BattlefieldSide :ally-side="true"></BattlefieldSide>
        <BattlefieldSide :ally-side="false"></BattlefieldSide>
    </svg>
</template>

<script>
    import BattlefieldSide from "./BattlefieldSide";
    export default {
        name: "CombatBattlefield",
        components: {BattlefieldSide}
    }
</script>

<style scoped>

</style>
