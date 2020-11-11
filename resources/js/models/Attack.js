
export default class Attack {

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

        this.name = name;
        this.baseDamage = baseDamage;
        this.combatSpeed = combatSpeed;
        this.damageMultiplier = damageMultiplier;
        this.tier = tier;
        this.attackerPositionID = attackerPositionID;
        this.targetPositionID = targetPositionID;
        this.damageTypeID = damageTypeID;
        this.targetPriorityID = targetPriorityID;
        this.targetsCount = targetsCount;
        this.resourceCosts = resourceCosts;
        this.requirments = requirements;
    }
}
