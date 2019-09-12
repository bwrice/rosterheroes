<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:25 PM
 */

namespace App\Domain\Behaviors\ItemBases;


use App\Domain\Behaviors\ItemGroup\EyeWearGroup;
use App\Domain\Behaviors\ItemGroup\ItemGroupInterface;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\SlotType;

class EyeWearBehavior extends ItemBaseBehavior
{
    // TODO: Implement eye-wear
    public function __construct(EyeWearGroup $eyeWearGroup)
    {
        parent::__construct($eyeWearGroup);
    }

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::HEAD
        ];
    }

    public function getBaseDamageBonus(UsesItems $usesItems = null): float
    {
        return 0;
    }

    public function getCombatSpeedModifier(UsesItems $hasItems = null): float
    {
        return 0;
    }

    public function getDamageMultiplierBonus(UsesItems $usesItems = null): float
    {
        return 0;
    }
}
