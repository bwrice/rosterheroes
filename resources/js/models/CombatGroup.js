import Combatant from "./Combatant";

export default class CombatGroup {

    constructor({combatants = []}) {
        this.combatants = combatants.map(combatant => new Combatant(combatant));
    }

    getHealthSum({combatPositionID, healthProperty}) {
        return this.combatants.filter(combatant => combatant.combatPositionID === combatPositionID)
            .map(combatant => combatant[healthProperty])
            .reduce(function (health, sum) {
                return health + sum;
            }, 0);
    }

}
