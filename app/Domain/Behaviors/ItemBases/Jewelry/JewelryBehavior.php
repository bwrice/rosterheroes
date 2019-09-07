<?php


namespace App\Domain\Behaviors\ItemBases\Jewelry;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\JewelryGroup;

abstract class JewelryBehavior extends ItemBaseBehavior
{
    public function __construct(JewelryGroup $jewelryGroup)
    {
        parent::__construct($jewelryGroup);
    }

    public function adjustDamageModifier(float $damageModifier): float
    {
        return $damageModifier;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage;
    }

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed;
    }
}
