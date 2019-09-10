<?php


namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ArmorGroup;
use App\Domain\Interfaces\HasItems;

abstract class ArmorBehavior extends ItemBaseBehavior
{
    public function __construct(ArmorGroup $armorGroup)
    {
        parent::__construct($armorGroup);
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
