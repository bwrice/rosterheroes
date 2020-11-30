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
            // combatPositionID() {
            //     switch (this.combatPositionName) {
            //         case 'front-line':
            //             return 1;
            //         case 'back-line':
            //             return 2;
            //         case 'high-ground':
            //         default:
            //             return 3;
            //     }
            // },
            healthPercent() {
                return this._allyHealthPercents[this.combatPositionName];
                // let combatPositionID = this.combatPositionID();
                // let initialHealth = this._allyGroup.getHealthSum({
                //     combatPositionID: combatPositionID,
                //     healthProperty: 'initialHealth'
                // });
                // let currentHealth = this._allyGroup.getHealthSum({
                //     combatPositionID: combatPositionID,
                //     healthProperty: 'currentHealth'
                // });
                // return initialHealth ? (currentHealth/initialHealth) * 100 : 0;
            }
        }
    }
</script>

<style scoped>

</style>
