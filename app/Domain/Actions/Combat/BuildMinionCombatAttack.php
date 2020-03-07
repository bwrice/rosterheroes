<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Minion;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

class BuildMinionCombatAttack extends AbstractBuildCombatAttack
{

    /**
     * @param Attack $attack
     * @param Minion $minion
     * @param Collection|null $combatPositions
     * @param Collection|null $targetPriorities
     * @param Collection|null $damageTypes
     * @return MinionCombatAttack
     */
    public function execute(
        Attack $attack,
        Minion $minion,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        $damage = $this->calculateCombatDamage->execute($attack, $minion);
        return new MinionCombatAttack(
            $minion->uuid,
            $attack->name,
            $attack->uuid,
            $damage,
            $attack->getCombatSpeed(),
            $attack->getGrade(),
            $attack->getMaxTargetsCount(),
            $this->getAttackerPosition($attack, $combatPositions),
            $this->getTargetPosition($attack, $combatPositions),
            $this->getTargetPriority($attack, $targetPriorities),
            $this->getDamageType($attack, $damageTypes)
        );
    }
}
