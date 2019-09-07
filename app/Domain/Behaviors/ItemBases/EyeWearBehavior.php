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

    public function adjustBaseDamage(float $baseDamage): float
    {
        // TODO: Implement adjustBaseDamage() method.
    }

    public function adjustCombatSpeed(float $speed): float
    {
        // TODO: Implement adjustCombatSpeed() method.
    }

    public function adjustDamageModifier(float $damageModifier): float
    {
        // TODO: Implement adjustDamageModifier() method.
    }
}
