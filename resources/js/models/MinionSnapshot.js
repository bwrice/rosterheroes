import AttackSnapshot from "./AttackSnapshot";
import BaseMinion from "./BaseMinion";

export default class MinionSnapshot extends BaseMinion {

    constructor({
        fantasyPower,
        attackSnapshots = [],
        ...rest
    }) {
        super(rest);
        this.fantasyPower = fantasyPower;
        this.attackSnapshots = attackSnapshots.map(snapshot => new AttackSnapshot(snapshot));
    }
}
