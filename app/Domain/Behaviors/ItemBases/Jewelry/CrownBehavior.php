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

class CrownBehavior extends JewelryBehavior
{
    protected $weightMultiplier = 2.5;

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
}
