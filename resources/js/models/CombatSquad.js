import Combatant from "./Combatant";

export default class CombatSquad {

    constructor({combat_heroes = []}) {
        this.combat_heroes = combat_heroes.map(combatHero => new Combatant(combatHero));
    }
}
