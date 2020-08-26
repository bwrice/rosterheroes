<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;

class FixedTargetBehavior extends DamageTypeBehavior
{
    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return $fixedTargetCount ?: 1;
    }

    public function getDamagePerTarget(int $damage, int $targetsCount)
    {
        return $damage;
    }

    public function getInitialBaseDamage(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = sqrt($tier);
        $targetsCountMultiplier = 1/(1 + .25 * ($targetsCount - 1));
        return 10 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getInitialDamageMultiplier(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = sqrt($tier);
        $targetsCountMultiplier = 1/(1 + .25 * ($targetsCount - 1));
        return 2 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getInitialCombatSpeed(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = 1/sqrt($tier);
        $targetsCountMultiplier = 1/(1 + .5 * ($targetsCount - 1));
        return 10 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float
    {
        return $tier * sqrt($targetsCount);
    }

    public function getResourceCosts(int $tier, ?int $targetsCount): ResourceCostsCollection
    {
        $resourceCosts = new ResourceCostsCollection();

        if ($targetsCount === 1) {
            return $resourceCosts;
        }

        $staminaAmount = 5 + ($tier * ($targetsCount**3));
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaAmount);
        $resourceCosts->push($staminaCost);

        if ($tier > 1) {
            $manaAmount = 3 + ($tier * ($targetsCount**2));
            $manaCost = new FixedResourceCost(MeasurableType::MANA, $manaAmount);
            $resourceCosts->push($manaCost);
        }

        return $resourceCosts;
    }
}
