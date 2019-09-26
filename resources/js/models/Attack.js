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
                    attackerPosition,
                    targetPosition,
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
        this.attackerPosition = new CombatPosition(attackerPosition);
        this.targetPosition = new CombatPosition(targetPosition);
        this.damageType = new DamageType(damageType);
        this.targetPriority = new TargetPriority(targetPriority);
        this.resourceCosts = resourceCosts;
        this.requirments = requirements;
    }
}
