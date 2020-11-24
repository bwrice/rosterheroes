import Combatant from "./Combatant";

export default class SideQuestGroup {

    constructor({combat_minions = []}) {
        this.combat_minions = combat_minions.map(combatMinion => new Combatant(combatMinion));
    }
}
