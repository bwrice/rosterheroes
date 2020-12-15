import Combatant from "./Combatant";
import CombatGroup from "./CombatGroup";

export default class CombatSquad extends CombatGroup {

    constructor({combat_heroes = []}) {
        super({
            combatants: combat_heroes
        });
    }
}
