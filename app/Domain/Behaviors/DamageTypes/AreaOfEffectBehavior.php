<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;

class AreaOfEffectBehavior extends DamageTypeBehavior
{

    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return (int) (3 + ceil($grade/30));
    }

    public function getDamagePerTarget(int $damage, int $targetsCount)
    {
        return $damage;
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
        $tierMultiplier = 1/sqrt($tier);
        return 4 * $tierMultiplier;
    }

    public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float
    {
        return 2 * $tier;
    }

    public function getResourceCosts(int $tier, ?int $targetsCount): ResourceCostsCollection
    {
        $resourceCosts = new ResourceCostsCollection();

        $staminaAmount = 10 + (2 * ($tier ** 2));
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaAmount);
        $resourceCosts->push($staminaCost);

        $manaAmount = ceil(8 + (1.5 * ($tier ** 2)));
        $manaCost = new FixedResourceCost(MeasurableType::MANA, $manaAmount);
        $resourceCosts->push($manaCost);

        return $resourceCosts;
    }
}
