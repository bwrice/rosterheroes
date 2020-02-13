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
    public function execute(Attack $attack, Item $item, Hero $hero, float $fantasyPower, Collection $combatPositions, Collection $targetPriorities, Collection $damageTypes)
    {
        $attackerPosition = $combatPositions->first(function (CombatPosition $combatPosition) use ($attack) {
            return $combatPosition->id === $attack->attacker_position_id;
        });
        $targetPosition = $combatPositions->first(function (CombatPosition $combatPosition) use ($attack) {
            return $combatPosition->id === $attack->target_position_id;
        });
        $targetPriority = $targetPriorities->first(function (TargetPriority $targetPriority) use ($attack) {
            return $targetPriority->id === $attack->target_priority_id;
        });
        $damageType = $damageTypes->first(function (DamageType $damageType) use ($attack) {
            return $damageType->id === $attack->damage_type_id;
        });
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
