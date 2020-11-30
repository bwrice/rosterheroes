<template>
    <BattlefieldEventGroup
        :combat-position-name="combatPositionName"
        :ally-side="true"
        :damages="damages"
        :blocks="blocks"
    ></BattlefieldEventGroup>
</template>

<script>

    import {mapGetters} from 'vuex';
    import BattlefieldEventGroup from "./BattlefieldEventGroup";

    export default {
        name: "AllyEventGroup",
        components: {BattlefieldEventGroup},
        props: {
            combatPositionName: {
                type: String,
                required: true
            },
        },
        computed: {
            ...mapGetters([
                '_allyDamages',
                '_allyBlocks'
            ]),
            damages() {
                return this._allyDamages[this.combatPositionName];
            },
            blocks() {
                return this._allyBlocks[this.combatPositionName];
            }
        }
    }
</script>

<style scoped>

</style>
