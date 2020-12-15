export default class BaseMinion {

    constructor({
        uuid,
        level,
        name = '',
        combatPositionID,
        enemyTypeID,
        startingHealth,
        startingStamina,
        startingMana,
        protection,
        blockChance,
        count,
    }) {
        this.uuid = uuid;
        this.name = name;
        this.level = level;
        this.combatPositionID = combatPositionID;
        this.enemyTypeID = enemyTypeID;
        this.startingHealth = startingHealth;
        this.startingStamina = startingStamina;
        this.startingMana = startingMana;
        this.protection = protection;
        this.blockChance = blockChance;
        this.count = count;
    }

}
