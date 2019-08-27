<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:17 PM
 */

namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Interfaces\ItemBehavior;
use App\Domain\Models\SlotType;
use Illuminate\Support\Arr;

abstract class ItemBaseBehavior implements ItemBehavior
{
    abstract public function getSlotTypeNames(): array;

    public function getSlotTypeIDs(): array
    {
        $slotTypes = $this->getSlotTypeNames();
        $slotTypes[] = SlotType::UNIVERSAL;

        return SlotType::query()
            ->whereIn('name', $slotTypes)
            ->pluck('id')
            ->toArray();
    }
}
