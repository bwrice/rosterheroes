<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\CombatMinion;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Collection;

class BuildCombatMinion
{
    public function execute(
        Minion $minion,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $minionCombatPosition = $combatPositions->first(function (CombatPosition $combatPosition) use ($minion) {
            return $combatPosition->id === $minion->combat_position_id;
        });
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();
        return new CombatMinion(
            $minion->id,
            $minion->getStartingHealth(),
            $minion->getProtection(),
            $minion->getBlockChance(),
            $minionCombatPosition,
            collect()
        );
    }
}
