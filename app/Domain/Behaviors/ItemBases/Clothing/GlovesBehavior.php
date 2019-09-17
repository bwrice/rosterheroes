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

class GlovesBehavior extends ClothingBehavior
{
    protected $weightModifier = 1.6;
    protected $protectionModifier = 1.15;

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::HANDS
        ];
    }
}
