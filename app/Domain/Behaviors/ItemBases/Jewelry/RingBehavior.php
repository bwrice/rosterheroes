<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:30 PM
 */

namespace App\Domain\Behaviors\ItemBases\Jewelry;


use App\Domain\Behaviors\ItemBases\Jewelry\JewelryBehavior;
use App\Domain\Models\SlotType;

class RingBehavior extends JewelryBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_RING,
            SlotType::RIGHT_RING
        ];
    }
}
