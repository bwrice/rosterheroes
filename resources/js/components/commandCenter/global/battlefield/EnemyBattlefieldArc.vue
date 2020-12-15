<template>
    <BattlefieldArc
        :ally-side="false"
        :combat-position-name="combatPositionName"
        :health-percent="healthPercent"
    ></BattlefieldArc>
</template>

<script>
    import {mapGetters} from 'vuex';
    import BattlefieldArc from "./BattlefieldArc";

    export default {
        name: "EnemyBattlefieldArc",
        components: {BattlefieldArc},
        props: {
            combatPositionName: {
                type: String,
                required: true
            },
        },
        computed: {
            ...mapGetters([
                '_enemyHealthPercents'
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
                return this._enemyHealthPercents[this.combatPositionName];
                // let combatPositionID = this.combatPositionID();
                // let initialHealth = this._enemyGroup.getHealthSum({
                //     combatPositionID: combatPositionID,
                //     healthProperty: 'initialHealth'
                // });
                // let currentHealth = this._enemyGroup.getHealthSum({
                //     combatPositionID: combatPositionID,
                //     healthProperty: 'currentHealth'
                // });
                // return initialHealth ? (currentHealth/initialHealth) * 100 : 0;
            }
        }
    }
</script>
