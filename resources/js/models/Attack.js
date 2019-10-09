import CombatPosition from "./CombatPosition";
import DamageType from "./DamageType";
import TargetPriority from "./TargetPriority";

export default class Attack {

    constructor({
                    name = '',
                    baseDamage,
                    combatSpeed,
                    damageMultiplier,
                    grade,
                    attackerPositionID,
                    targetPositionID,
                    damageType,
                    targetPriority,
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
        this.damageType = new DamageType(damageType);
        this.targetPriority = new TargetPriority(targetPriority);
        this.resourceCosts = resourceCosts;
        this.requirments = requirements;
    }
}
