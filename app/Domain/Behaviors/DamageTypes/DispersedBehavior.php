<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use Illuminate\Support\Collection;

class DispersedBehavior extends DamageTypeBehavior
{

    public function getMaxTargetCount(int $tier, ?int $fixedTargetCount)
    {
        return (int) (4 + ceil($tier/15));
    }

    public function getDamagePerTarget(int $totalDamage, int $targetsCount)
    {
        if ($targetsCount > 0) {
            return (int) ceil($totalDamage/$targetsCount);
        }
        return 0;
    }

    public function getInitialBaseDamage(int $tier, ?int $targetsCount): float
    {
        return sqrt($tier) * 40;
    }

    public function getInitialDamageMultiplier(int $tier, ?int $targetsCount): float
    {
        return sqrt($tier) * 8;
    }

    public function getInitialCombatSpeed(int $tier, ?int $targetsCount): float
    {
        return .8;
    }

    public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float
    {
        return 2.5 * $tier;
    }

    public function getResourceCosts(int $tier, ?int $targetsCount): Collection
    {
        $resourceCosts = collect();

        $staminaAmount = 14 + (2 * ($tier ** 2.5));
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaAmount);
        $resourceCosts->push($staminaCost);

        $manaAmount = ceil(10 + (1.5 * ($tier ** 2.5)));
        $manaCost = new FixedResourceCost(MeasurableType::MANA, $manaAmount);
        $resourceCosts->push($manaCost);

        return $resourceCosts;
    }
}
