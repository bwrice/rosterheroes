<?php


namespace App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors;


interface ArmBehaviorInterface
{
    public function getSlotsCount(): int;

    public function getDamageMultiplierModifierBonus(): float;

    public function getBaseDamageModifierBonus(): float;

    public function getCombatSpeedModifierBonus(): float;

    public function getResourceCostAmountModifier(): float;

    public function getResourceCostPercentModifier(): float;
}
