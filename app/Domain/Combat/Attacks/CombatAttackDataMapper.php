<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

class CombatAttackDataMapper
{
    public function getCombatAttack(array $data, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        return new CombatAttack(
            $data['name'],
            $data['attackUuid'],
            $data['damage'],
            $data['combatSpeed'],
            $data['grade'],
            $combatPositions->find($data['attackerPositionID']),
            $combatPositions->find($data['targetPositionID']),
            $targetPriorities->find($data['targetPriorityID']),
            $damageTypes->find($data['damageTypeID']),
            $data['maxTargetsCount']
        );
    }
}
