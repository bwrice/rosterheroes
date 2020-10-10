<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use Illuminate\Support\Facades\Password;

class FixedTargetBehavior extends DamageTypeBehavior
{
    public function getMaxTargetCount(int $tier, ?int $fixedTargetCount)
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
        $targetsCountMultiplier = 1/(1 + .5 * ($targetsCount - 1));
        return 10 * $targetsCountMultiplier;
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

        $staminaAmount = 5 + (10 * $targetsCount**.8 * $tier**2);
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaAmount);
        $resourceCosts->push($staminaCost);

        $manaAmount = 2 + (3 * $targetsCount**.8 * $tier**2);
        $manaCost = new FixedResourceCost(MeasurableType::MANA, $manaAmount);
        $resourceCosts->push($manaCost);

        return $resourceCosts;
    }
}
