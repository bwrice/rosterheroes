<?php


namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ArmorGroup;
use App\Domain\Interfaces\UsesItems;

abstract class ArmorBehavior extends ItemBaseBehavior
{
    public function __construct(ArmorGroup $armorGroup)
    {
        parent::__construct($armorGroup);
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
