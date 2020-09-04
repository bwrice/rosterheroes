<?php


namespace App\Domain\Behaviors\ItemBases\Shields;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ShieldGroup;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Support\GearSlots\GearSlot;

abstract class ShieldGroupBehavior extends ItemBaseBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::OFF_ARM
    ];

    public function __construct(ShieldGroup $shieldGroup)
    {
        parent::__construct($shieldGroup);
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

    public function adjustResourceCosts(ResourceCostsCollection $resourceCosts): ResourceCostsCollection
    {
        return $resourceCosts;
    }

}
