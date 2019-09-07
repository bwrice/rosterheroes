<?php


namespace App\Domain\Behaviors\ItemBases\Clothing;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ClothingGroup;

abstract class ClothingBehavior extends ItemBaseBehavior
{
    public function __construct(ClothingGroup $clothingGroup)
    {
        parent::__construct($clothingGroup);
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
