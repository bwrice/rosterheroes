<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Interfaces\HasFantasyPoints;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\Services\FantasyPower;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AbstractBuildCombatAttack
 * @package App\Domain\Actions\Combat
 * @deprecated
 */
abstract class AbstractBuildCombatAttack
{

    /**
     * @var CalculateCombatDamage
     */
    protected $calculateCombatDamage;

    public function __construct(CalculateCombatDamage $calculateCombatDamage)
    {
        $this->calculateCombatDamage = $calculateCombatDamage;
    }

    protected function getAttackerPosition(Attack $attack, Collection $allCombatPositions)
    {
        /** @var CombatPosition $attackerPosition */
        $attackerPosition = $allCombatPositions->find($attack->attacker_position_id);
        return $attackerPosition;
    }

    protected function getTargetPosition(Attack $attack, Collection $allCombatPositions)
    {
        /** @var CombatPosition $attackerPosition */
        $attackerPosition = $allCombatPositions->find($attack->target_position_id);
        return $attackerPosition;
    }

    protected function getTargetPriority(Attack $attack, Collection $allTargetPriorities)
    {
        /** @var TargetPriority $targetPriority */
        $targetPriority = $allTargetPriorities->find($attack->target_priority_id);
        return $targetPriority;
    }

    protected function getDamageType(Attack $attack, Collection $allDamageTypes)
    {
        /** @var DamageType $damageType */
        $damageType = $allDamageTypes->find($attack->damage_type_id);
        return $damageType;
    }

}
