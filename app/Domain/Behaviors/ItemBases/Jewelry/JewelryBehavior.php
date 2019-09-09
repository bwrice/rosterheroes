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
