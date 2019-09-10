<?php


namespace App\Domain\Behaviors\ItemBases\Clothing;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ClothingGroup;
use App\Domain\Interfaces\HasItems;

abstract class ClothingBehavior extends ItemBaseBehavior
{
    public function __construct(ClothingGroup $clothingGroup)
    {
        parent::__construct($clothingGroup);
    }

    public function getDamageMultiplierModifier(HasItems $hasItems = null): float
    {
        return 1;
    }

    public function getBaseDamageModifier(HasItems $hasItems = null): float
    {
        return 1;
    }

    public function getCombatSpeedModifier(HasItems $hasItems = null): float
    {
        return 1;
    }
}
