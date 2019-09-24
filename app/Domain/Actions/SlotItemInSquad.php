<?php


namespace App\Domain\Actions;


use App\Domain\Models\Item;
use App\Domain\Models\Squad;

class SlotItemInSquad
{
    public function execute(Squad $squad, Item $item)
    {
        $slotsNeeded = $item->getSlotsCount();
        $squadSlots = $squad->slots->slotEmpty();
        if ($squadSlots->count() < $slotsNeeded) {
            $stash = $squad->getLocalStash();
            $slotsToFill = $stash->getEmptySlots($slotsNeeded);
            $item->slots()->saveMany($slotsToFill);
        } else {
            $slotsToFill = $squadSlots->take($slotsNeeded);
            $item->slots()->saveMany($slotsToFill);
        }
    }
}
