<?php


namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ArmorGroup;

abstract class ArmorBehavior extends ItemBaseBehavior
{
    public function __construct(ArmorGroup $armorGroup)
    {
        parent::__construct($armorGroup);
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
