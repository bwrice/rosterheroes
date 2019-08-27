<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:27 PM
 */

namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Models\SlotType;

class BeltBehavior extends ArmorBehavior
{

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
