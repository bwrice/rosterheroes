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

class RobesBehavior extends ClothingBehavior
{
    protected $weightModifier = 4;
    protected $protectionModifier = 3.5;

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
