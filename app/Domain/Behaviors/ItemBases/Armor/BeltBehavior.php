<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:27 PM
 */

namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\Armor\ArmorBehavior;
use App\Domain\Models\SlotType;

class BeltBehavior extends ArmorBehavior
{
    protected $weightModifier = 2.4;
    protected $protectionModifier = 1.5;

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::WAIST
        ];
    }
}
