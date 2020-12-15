import BaseAttack from "./BaseAttack";

export default class AttackSnapshot extends BaseAttack{

    constructor({
        name = '',
        uuid,
        combatSpeed,
        damage,
        attackerPositionID = 0,
        targetPositionID = 0,
        damageTypeID = 0,
        targetPriorityID = 0,
        targetsCount,
        tier,
        resourceCosts = [],
        requirements = []
    }) {
        super({
            name,
            combatSpeed,
            attackerPositionID,
            targetPositionID,
            damageTypeID,
            targetPriorityID,
            targetsCount,
            tier,
            resourceCosts,
            requirements
        });
        this.uuid = uuid;
        this.damage = damage;
    }
}
