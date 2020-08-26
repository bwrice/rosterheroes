<?php


namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ArmorGroup;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\UsesItems;

abstract class ArmorBehavior extends ItemBaseBehavior
{
    protected $blockChanceModifier = 0;

    public function __construct(ArmorGroup $armorGroup)
    {
        parent::__construct($armorGroup);
    }

    public function getDamageMultiplierBonus(UsesItems $usesItems = null): float
    {
        return 0;
    }

    public function getBaseDamageBonus(UsesItems $usesItems = null): float
    {
        return 0;
    }

    public function getCombatSpeedBonus(UsesItems $hasItems = null): float
    {
        return 0;
    }

    public function adjustBaseDamage(float $baseDamage, UsesItems $usesItems = null): float
    {
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageMultiplier, UsesItems $usesItems = null): float
    {
        return $damageMultiplier;
    }

    public function adjustCombatSpeed(float $combatSpeed, UsesItems $hasItems = null): float
    {
        return $combatSpeed;
    }

    public function getResourceCosts(int $attackTier, float $resourceCostMagnitude)
    {
        return new ResourceCostsCollection();
    }

    public function adjustResourceCosts(ResourceCostsCollection $resourceCosts): ResourceCostsCollection
    {
        return $resourceCosts;
    }
}
