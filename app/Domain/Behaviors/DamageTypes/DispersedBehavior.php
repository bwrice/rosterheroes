<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;

class DispersedBehavior extends DamageTypeBehavior
{

    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return (int) (4 + ceil($grade/15));
    }

    public function getDamagePerTarget(int $damage, int $targetsCount)
    {
        if ($targetsCount > 0) {
            return (int) ceil($damage/$targetsCount);
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
        $tierMultiplier = 1/sqrt($tier);
        return 2 * $tierMultiplier;
    }

    public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float
    {
        return 2.5 * $tier;
    }

    public function getResourceCosts(int $tier, ?int $targetsCount): ResourceCostsCollection
    {
        $resourceCosts = new ResourceCostsCollection();

        $staminaAmount = 14 + (2 * ($tier ** 2));
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaAmount);
        $resourceCosts->push($staminaCost);

        $manaAmount = ceil(10 + (1.5 * ($tier ** 2)));
        $manaCost = new FixedResourceCost(MeasurableType::MANA, $manaAmount);
        $resourceCosts->push($manaCost);

        return $resourceCosts;
    }
}
