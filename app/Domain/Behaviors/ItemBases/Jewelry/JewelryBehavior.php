<?php


namespace App\Domain\Behaviors\ItemBases\Jewelry;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\JewelryGroup;
use App\Domain\Interfaces\UsesItems;

abstract class JewelryBehavior extends ItemBaseBehavior
{
    public function __construct(JewelryGroup $jewelryGroup)
    {
        parent::__construct($jewelryGroup);
    }

    public function getDamageMultiplierModifier(UsesItems $hasItems = null): float
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
