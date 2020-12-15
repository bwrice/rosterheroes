import BaseAttack from "./BaseAttack";

export default class Attack extends BaseAttack {

    constructor({
        name = '',
        baseDamage,
        combatSpeed,
        damageMultiplier,
        tier,
        attackerPositionID,
        targetPositionID,
        damageTypeID,
        targetPriorityID,
        targetsCount,
        resourceCosts = [],
        requirements = [],
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
        this.baseDamage = baseDamage;
        this.damageMultiplier = damageMultiplier;
    }
}
