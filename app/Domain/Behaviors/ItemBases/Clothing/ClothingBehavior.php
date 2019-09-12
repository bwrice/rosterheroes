<?php


namespace App\Domain\Behaviors\ItemBases\Clothing;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ClothingGroup;
use App\Domain\Interfaces\UsesItems;

abstract class ClothingBehavior extends ItemBaseBehavior
{
    public function __construct(ClothingGroup $clothingGroup)
    {
        parent::__construct($clothingGroup);
    }

    public function getDamageMultiplierBonus(UsesItems $usesItems = null): float
    {
        return 1;
    }

    public function getBaseDamageModifier(UsesItems $usesItems = null): float
    {
        return 1;
    }

    public function getCombatSpeedModifier(UsesItems $hasItems = null): float
    {
        return 1;
    }
}
