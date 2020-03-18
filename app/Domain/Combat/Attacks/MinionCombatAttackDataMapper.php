<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

class MinionCombatAttackDataMapper extends AbstractCombatAttackDataMapper
{

    public function getMinionCombatAttack(array $data, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        return new MinionCombatAttack(
            $data['minionUuid'],
            $data['combatantUuid'],
            $this->getName($data),
            $this->getAttackUuid($data),
            $this->getDamage($data),
            $this->getCombatSpeed($data),
            $this->getGrade($data),
            $this->getMaxTargetsCount($data),
            $this->getAttackerPosition($data, $combatPositions),
            $this->getTargetPosition($data, $combatPositions),
            $this->getTargetPriority($data, $targetPriorities),
            $this->getDamageTypes($data, $damageTypes),
        );
    }
}
