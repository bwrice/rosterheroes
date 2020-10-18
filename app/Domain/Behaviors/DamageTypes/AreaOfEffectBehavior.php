<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use Illuminate\Support\Collection;

class AreaOfEffectBehavior extends DamageTypeBehavior
{

    public function getMaxTargetCount(int $tier, ?int $fixedTargetCount)
    {
        return (int) (3 + ceil($tier/30));
    }

    public function getDamagePerTarget(int $totalDamage, int $targetsCount)
    {
        return $totalDamage;
    }

    public function getInitialBaseDamage(int $tier, ?int $targetsCount): float
    {
        return sqrt($tier) * 5;
    }

    public function getInitialDamageMultiplier(int $tier, ?int $targetsCount): float
    {
        return sqrt($tier);
    }

    public function getInitialCombatSpeed(int $tier, ?int $targetsCount): float
    {
        return 1.5;
    }

    public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float
    {
        return 2 * $tier;
    }

    public function getResourceCosts(int $tier, ?int $targetsCount): Collection
    {
        $resourceCosts = collect();

        $staminaAmount = (int) ceil(10 + (25 * $tier**2));
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaAmount);
        $resourceCosts->push($staminaCost);

        $manaAmount = (int) ceil(8 + (20 * $tier**2));
        $manaCost = new FixedResourceCost(MeasurableType::MANA, $manaAmount);
        $resourceCosts->push($manaCost);

        return $resourceCosts;
    }
}
