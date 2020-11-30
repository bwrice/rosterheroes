<template>
    <BattlefieldEventGroup
        :combat-position-name="combatPositionName"
        :ally-side="false"
        :damages="damages"
        :blocks="blocks"
    ></BattlefieldEventGroup>
</template>

<script>

    import {mapGetters} from 'vuex';
    import BattlefieldEventGroup from "./BattlefieldEventGroup";

    export default {
        name: "EnemyEventGroup",
        components: {BattlefieldEventGroup},
        props: {
            combatPositionName: {
                type: String,
                required: true
            },
        },
        computed: {
            ...mapGetters([
                '_enemyDamages',
                '_enemyBlocks'
            ]),
            damages() {
                return this._enemyDamages[this.combatPositionName];
            },
            blocks() {
                return this._enemyBlocks[this.combatPositionName];
            }
        }
    }
</script>

<style scoped>

</style>
