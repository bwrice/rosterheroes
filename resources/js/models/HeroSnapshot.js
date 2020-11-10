import PlayerSpirit from "./PlayerSpirit";
import AttackSnapshot from "./AttackSnapshot";

export default class HeroSnapshot {

    constructor({
        name = '',
        uuid,
        heroClassID = 0,
        heroRaceID = 0,
        combatPositionID = 0,
        health,
        stamina,
        mana,
        protection,
        blockChance,
        fantasyPower,
        playerSpirit = {},
        attackSnapshots = []
    }) {
        this.name = name;
        this.uuid = uuid;
        this.heroClassID = heroClassID;
        this.heroRaceID = heroRaceID;
        this.combatPositionID = combatPositionID;
        this.health = health;
        this.stamina = stamina;
        this.mana = mana;
        this.protection = protection;
        this.blockChance = blockChance;
        this.fantasyPower = fantasyPower;
        this.playerSpirit = new PlayerSpirit(playerSpirit);
        this.attackSnapshots = attackSnapshots.map(snapshot => new AttackSnapshot(snapshot));
    }
}
