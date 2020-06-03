
export default class Attack {

    constructor({
                    name = '',
                    baseDamage,
                    combatSpeed,
                    damageMultiplier,
                    grade,
                    attackerPositionID,
                    targetPositionID,
                    damageTypeID,
                    targetPriorityID,
                    resourceCosts = [],
                    requirements = [],
                }) {

        this.name = name;
        this.baseDamage = baseDamage;
        this.combatSpeed = combatSpeed;
        this.damageMultiplier = damageMultiplier;
        this.grade = grade;
        this.attackerPositionID = attackerPositionID;
        this.targetPositionID = targetPositionID;
        this.damageTypeID = damageTypeID;
        this.targetPriorityID = targetPriorityID;
        this.resourceCosts = resourceCosts;
        this.requirments = requirements;
    }
}
