<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

class BuildHeroCombatAttack
{
    public function execute(Attack $attack, Item $item, Hero $hero, float $fantasyPower, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        $attackerPosition = $combatPositions->find($attack->attacker_position_id);
        $targetPosition = $combatPositions->find($attack->target_position_id);
        $targetPriority = $targetPriorities->find($attack->target_priority_id);
        $damageType = $damageTypes->find($attack->damage_type_id);
        $damage = $this->calculateAttackDamage($attack, $fantasyPower);
        return new HeroCombatAttack(
            $attack->name,
            $hero->id,
            $item->id,
            $attack->id,
            $damage,
            $attack->getGrade(),
            $attack->getCombatSpeed(),
            $attackerPosition,
            $targetPosition,
            $targetPriority,
            $damageType,
            $attack->getResourceCostCollection(),
            $attack->getMaxTargetsCount()
        );
    }

    protected function calculateAttackDamage(Attack $attack, float $fantasyPower)
    {
        $baseDamage = $attack->getBaseDamage();
        $damageMultiplier = $attack->getDamageMultiplier();
        return (int) max(ceil($baseDamage + ($damageMultiplier * $fantasyPower)), 1);
    }
}
