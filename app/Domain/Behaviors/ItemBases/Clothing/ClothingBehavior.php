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

    public function getDamageMultiplierModifier(): float
    {
        return 1;
    }

    public function getBaseDamageModifier(): float
    {
        return 1;
    }

    public function getCombatSpeedModifier(): float
    {
        return 1;
    }
}
