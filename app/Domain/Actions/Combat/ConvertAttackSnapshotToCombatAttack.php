<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Models\AttackSnapshot;

class ConvertAttackSnapshotToCombatAttack
{
    public function execute(AttackSnapshot $attackSnapshot)
    {
        return new CombatAttack(
            $attackSnapshot->name,
            $attackSnapshot->uuid,
            $attackSnapshot->attacker->getUuid(),
            $attackSnapshot->damage,
            $attackSnapshot->combat_speed,
            $attackSnapshot->tier,
            $attackSnapshot->targets_count,
            $attackSnapshot->attacker_position_id,
            $attackSnapshot->target_position_id,
            $attackSnapshot->target_priority_id,
            $attackSnapshot->damage_type_id,
            $attackSnapshot->resource_costs
        );
    }
}
