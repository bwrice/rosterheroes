<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use Illuminate\Support\Collection;

class FixedTargetBehavior extends DamageTypeBehavior
{
    public function getMaxTargetCount(int $tier, ?int $fixedTargetCount)
    {
        return $fixedTargetCount ?: 1;
    }

    public function getDamagePerTarget(int $totalDamage, int $targetsCount)
    {
        return $totalDamage;
    }

    public function getInitialBaseDamage(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = $tier**.15;
        $targetsCountMultiplier = 1/(1 + .25 * ($targetsCount - 1));
        return 10 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getInitialDamageMultiplier(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = $tier**.15;
        $targetsCountMultiplier = 1/(1 + .25 * ($targetsCount - 1));
        return 2 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getInitialCombatSpeed(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $targetsCountMultiplier = 1/(1 + 2 * ($targetsCount - 1));
        return 5 * $targetsCountMultiplier;
    }

    public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float
    {
        return $tier * sqrt($targetsCount);
    }

    public function getResourceCosts(int $tier, ?int $targetsCount): Collection
    {
        $resourceCosts = collect();

        if ($targetsCount === 1) {
            return $resourceCosts;
        }

        $staminaAmount = 5 + (10 * $targetsCount**.8 * $tier**2);
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaAmount);
        $resourceCosts->push($staminaCost);

        $manaAmount = 2 + (3 * $targetsCount**.8 * $tier**2);
        $manaCost = new FixedResourceCost(MeasurableType::MANA, $manaAmount);
        $resourceCosts->push($manaCost);

        return $resourceCosts;
    }
}
