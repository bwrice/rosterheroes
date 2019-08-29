<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:28 PM
 */

namespace App\Domain\Behaviors\ItemBases;


use App\Domain\Models\SlotType;

class BraceletBehavior extends JewelryBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_WRIST,
            SlotType::RIGHT_WRIST
        ];
    }
}
