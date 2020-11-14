import AttackSnapshot from "./AttackSnapshot";

export default class MinionSnapshot {

    constructor({
        uuid,
        name = '',
        level,
        combatPositionID,
        enemyTypeID,
        startingHealth,
        startingStamina,
        startingMana,
        protection,
        blockChance,
        fantasyPower,
        attackSnapshots = [],
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
        this.fantasyPower = fantasyPower;
        this.attackSnapshots = attackSnapshots.map(snapshot => new AttackSnapshot(snapshot));
        this.count = count;
    }
}
