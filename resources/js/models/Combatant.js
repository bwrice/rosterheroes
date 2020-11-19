import CombatAttack from "./CombatAttack";

export default class Combatant {

    constructor({
        combatant_uuid,
        source_uuid,
        block_chance_percent,
        protection,
        combat_position_id,
        initial_health,
        initial_stamina,
        initial_mana,
        combat_attacks = []
    }) {
        this.combantantUuid = combatant_uuid;
        this.sourceUuid = source_uuid;
        this.blockChancePercent = block_chance_percent;
        this.protection = protection;
        this.combatPositionID = combat_position_id;
        this.currentHealth = this.initialHealth = initial_health;
        this.currentStamina = this.initialStamina = initial_stamina;
        this.currentMana = this.initial_mana = initial_mana;
        this.combatAttacks = combat_attacks.map(attack => new CombatAttack(attack));
    }
}
