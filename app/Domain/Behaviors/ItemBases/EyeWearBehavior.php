<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:25 PM
 */

namespace App\Domain\Behaviors\ItemBases;


use App\Domain\Behaviors\ItemGroup\EyeWearGroup;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Support\GearSlots\GearSlot;

class EyeWearBehavior extends ItemBaseBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::HEAD
    ];

    public function __construct(EyeWearGroup $eyeWearGroup)
    {
        parent::__construct($eyeWearGroup);
    }

    public function getBaseDamageBonus(UsesItems $usesItems = null): float
    {
        return 0;
    }

    public function getCombatSpeedBonus(UsesItems $hasItems = null): float
    {
        return 0;
    }

    public function getDamageMultiplierBonus(UsesItems $usesItems = null): float
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
}
