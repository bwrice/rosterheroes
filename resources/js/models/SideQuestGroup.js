import CombatGroup from "./CombatGroup";

export default class SideQuestGroup extends CombatGroup {

    constructor({combat_minions = []}) {
        super({
            combatants: combat_minions
        });
    }
}
