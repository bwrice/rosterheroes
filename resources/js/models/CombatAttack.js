
export default class CombatAttack {

    constructor({attacker_uuid, combat_attack_uuid, source_uuid}) {
        this.attackerUuid = attacker_uuid;
        this.combatAttackUuid = combat_attack_uuid;
        this.sourceUuid = source_uuid;
    }
}
