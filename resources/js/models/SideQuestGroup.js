import Combatant from "./Combatant";
import CombatGroup from "./CombatGroup";

export default class SideQuestGroup extends CombatGroup {

    constructor({combat_minions = []}) {
        super({
            combatants: combat_minions
        });
        this.combatMinions = combat_minions.map(combatMinion => new Combatant(combatMinion));
    }
}
