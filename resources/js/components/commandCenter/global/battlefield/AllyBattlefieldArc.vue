<template>
    <BattlefieldArc
        :ally-side="true"
        :combat-position-name="combatPositionName"
        :health-percent="healthPercent"
    ></BattlefieldArc>
</template>

<script>
    import {mapGetters} from 'vuex';
    import BattlefieldArc from "./BattlefieldArc";

    export default {
        name: "AllyBattlefieldArc",
        components: {BattlefieldArc},
        props: {
            combatPositionName: {
                type: String,
                required: true
            },
        },
        computed: {
            ...mapGetters([
                '_allyHealthPercents'
            ]),
            healthPercent() {
                return this._allyHealthPercents[this.combatPositionName];
            }
        }
    }
</script>

<style scoped>

</style>
