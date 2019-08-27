<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:30 PM
 */

namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Models\SlotType;

class CrownBehavior extends JewelryBehavior
{

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
