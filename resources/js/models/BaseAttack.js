export default class BaseAttack {

    constructor({
        name = '',
        combatSpeed,
        attackerPositionID = 0,
        targetPositionID = 0,
        damageTypeID = 0,
        targetPriorityID = 0,
        targetsCount,
        tier,
        resourceCosts = [],
        requirements = []
    }) {
        this.name = name;
        this.combatSpeed = combatSpeed;
        this.attackerPositionID = attackerPositionID;
        this.targetPositionID = targetPositionID;
        this.damageTypeID = damageTypeID;
        this.targetPriorityID = targetPriorityID;
        this.targetsCount = targetsCount;
        this.tier = tier;
        this.resourceCosts = resourceCosts;
        this.requirements = requirements;
    }
}
