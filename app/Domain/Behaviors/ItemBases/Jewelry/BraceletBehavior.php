<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:28 PM
 */

namespace App\Domain\Behaviors\ItemBases\Jewelry;


use App\Domain\Behaviors\ItemBases\Jewelry\JewelryBehavior;
use App\Domain\Models\SlotType;

class BraceletBehavior extends JewelryBehavior
{

    protected $weightMultiplier = 1.3;

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
