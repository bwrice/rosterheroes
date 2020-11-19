import Combatant from "./Combatant";

export default class CombatGroup {

    constructor({combatants = []}) {
        this.combatants = combatants.map(combatant => new Combatant(combatant));
    }
}
