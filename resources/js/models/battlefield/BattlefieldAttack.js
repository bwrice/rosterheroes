import BattlefieldDamageEvent from "./BattlefieldDamageEvent";
import BattlefieldEvent from "./BattlefieldEvent";

export default class BattlefieldAttack extends BattlefieldEvent{

    constructor({battlefieldDamages = [], ...eventParams}) {
        super(eventParams);
        this.battlefieldDamages = battlefieldDamages.map(attackTarget => new BattlefieldDamageEvent(attackTarget));
    }
}
