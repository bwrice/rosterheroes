import Attack from "./Attack";

export default class Minion {

    constructor({uuid, slug, name, combatPositionID, level, startingHealth, protection, blockChance, attacks = [], count = 1}) {
        this.uuid = uuid;
        this.slug = slug;
        this.name = name;
        this.combatPositionID = combatPositionID;
        this.level = level;
        this.startingHealth = startingHealth;
        this.protection = protection;
        this.blockChance = blockChance;
        this.attacks = attacks.map(attack => new Attack(attack));
        this.count = count;
    }

}
