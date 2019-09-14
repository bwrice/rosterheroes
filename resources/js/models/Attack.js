
export default class Attack {

    constructor({name = '', base_damage, combat_speed, damage_multiplier, grade}) {
        this.name = name;
        this.baseDamage = base_damage;
        this.combatSpeed = combat_speed;
        this.damageMultiplier = damage_multiplier;
        this.grade = grade;
    }
}
