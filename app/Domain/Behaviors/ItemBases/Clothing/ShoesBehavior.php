<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:26 PM
 */

namespace App\Domain\Behaviors\ItemBases\Clothing;


use App\Domain\Behaviors\ItemBases\Clothing\ClothingBehavior;
use App\Domain\Models\SlotType;

class ShoesBehavior extends ClothingBehavior
{
    protected $weightMultiplier = 1.8;

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::FEET
        ];
    }
}
