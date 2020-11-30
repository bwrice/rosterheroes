import Combatant from "./Combatant";

export default class CombatGroup {

    constructor({combatants = []}) {
        this.combatants = combatants.map(combatant => new Combatant(combatant));
    }

    getHealthSum({combatPositionIDs = [], healthProperty}) {
        return this.combatants.filter(combatant => combatPositionIDs.includes(combatant.combatPositionID))
            .map(combatant => combatant[healthProperty])
            .reduce(function (health, sum) {
                return health + sum;
            }, 0);
    }

    getHealthPercent({combatPositionIDs = []}) {
        let initialHealth = this.getHealthSum({combatPositionIDs, healthProperty: 'initialHealth'});
        if (initialHealth > 0) {
            let currentHealth = this.getHealthSum({combatPositionIDs, healthProperty: 'currentHealth'});
            return (currentHealth/initialHealth) * 100;
        }
        return 0;
    }

    getHealthPercentsObject() {
        return {
            'front-line': this.getHealthPercent({combatPositionIDs: [1]}),
            'back-line': this.getHealthPercent({combatPositionIDs: [2]}),
            'high-ground': this.getHealthPercent({combatPositionIDs: [3]}),
        };
    }

}
