<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:26 PM
 */

namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\Armor\ArmorBehavior;
use App\Domain\Models\SlotType;

class LightArmorBehavior extends ArmorBehavior
{
    protected $weightMultiplier = 6.5;

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::TORSO
        ];
    }
}
