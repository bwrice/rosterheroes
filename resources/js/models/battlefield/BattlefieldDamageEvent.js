import BattlefieldEvent from "./BattlefieldEvent";

export default class BattlefieldDamageEvent extends BattlefieldEvent{

    constructor({damage, magnitude, ...eventParams}) {
        super(eventParams);
        this.damage = damage;
        this.magnitude = magnitude;
    }
}
