<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Minion;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Class BuildCombatMinion
 * @package App\Domain\Actions\Combat
 * @deprecated
 */
class BuildCombatMinion
{
    /**
     * @var BuildMinionCombatAttack
     */
    protected $buildMinionCombatAttack;

    public function __construct(BuildMinionCombatAttack $buildMinionCombatAttack)
    {
        $this->buildMinionCombatAttack = $buildMinionCombatAttack;
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
        $combatantUuid = (string) Str::uuid();
        $combatAttacks = $minion->attacks->map(function (Attack $attack) use ($minion, $combatantUuid, $combatPositions, $targetPriorities, $damageTypes) {
            return $this->buildMinionCombatAttack->execute($attack, $minion, $combatantUuid, $combatPositions, $targetPriorities, $damageTypes);
        });
        return new CombatMinion(
            $minion->uuid,
            $combatantUuid,
            $minion->getStartingHealth(),
            $minion->getProtection(),
            $minion->getBlockChance(),
            $minionCombatPosition,
            new CombatAttackCollection($combatAttacks)
        );
    }
}
