<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\CombatMinion;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Minion;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

class BuildCombatMinion
{
    /**
     * @var BuildCombatAttack
     */
    private $buildCombatAttack;

    public function __construct(BuildCombatAttack $buildCombatAttack)
    {
        $this->buildCombatAttack = $buildCombatAttack;
    }

    public function execute(
        Minion $minion,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        /** @var CombatPosition $minionCombatPosition */
        $minionCombatPosition = $combatPositions->find($minion->combat_position_id);
        $combatAttacks = $minion->attacks->map(function (Attack $attack) use ($minion, $combatPositions, $targetPriorities, $damageTypes) {
            return $this->buildCombatAttack->execute($attack, $minion, $combatPositions, $targetPriorities, $damageTypes);
        });
        return new CombatMinion(
            $minion->id,
            $minion->getStartingHealth(),
            $minion->getProtection(),
            $minion->getBlockChance(),
            $minionCombatPosition,
            $combatAttacks
        );
    }
}
