
export default class AttackSnapshot {

    constructor({
        name = '',
        uuid,
        attackerPositionID = 0,
        targetPositionID = 0,
        damageTypeID = 0,
        targetPriorityID = 0,
        targetsCount,
        tier,
        resourceCosts = []
    }) {
        this.name = name;
        this.uuid = uuid;
        this.attackerPositionID = attackerPositionID;
        this.targetPositionID = targetPositionID;
        this.damageTypeID = damageTypeID;
        this.targetPriorityID = targetPriorityID;
        this.targetsCount = targetsCount;
        this.tier = tier;
        this.resourceCosts = resourceCosts;
    }
}
