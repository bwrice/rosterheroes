import BattlefieldDamageEvent from "./BattlefieldDamageEvent";
import BattlefieldEvent from "./BattlefieldEvent";
import BattlefieldBlockEvent from "./BattlefieldBlockEvent";

export default class BattlefieldAttackEvent extends BattlefieldEvent{

    constructor({battlefieldDamages = [], battlefieldBlocks = [], ...eventParams}) {
        super(eventParams);
        this.battlefieldDamages = battlefieldDamages.map(damageEvent => new BattlefieldDamageEvent(damageEvent));
        this.battlefieldBlocks = battlefieldBlocks.map(blockEvent => new BattlefieldBlockEvent(blockEvent));
    }
}
