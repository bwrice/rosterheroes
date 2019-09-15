import CombatPosition from "./CombatPosition";
import DamageType from "./DamageType";
import TargetPriority from "./TargetPriority";

export default class Attack {

    constructor({
                    name = '',
                    base_damage,
                    combat_speed,
                    damage_multiplier,
                    grade,
                    attackerPosition,
                    targetPosition,
                    damageType,
                    targetPriority
                }) {

        this.name = name;
        this.baseDamage = base_damage;
        this.combatSpeed = combat_speed;
        this.damageMultiplier = damage_multiplier;
        this.grade = grade;
        this.attackerPosition = new CombatPosition(attackerPosition);
        this.targetPosition = new CombatPosition(targetPosition);
        this.damageType = new DamageType(damageType);
        this.targetPriority = new TargetPriority(targetPriority);
    }
}
