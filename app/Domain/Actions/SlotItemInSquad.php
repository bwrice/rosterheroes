<?php


namespace App\Domain\Actions;


use App\Domain\Models\Item;
use App\Domain\Models\Squad;

class SlotItemInSquad
{
    public function execute(Squad $squad, Item $item)
    {
        $slotsNeeded = $item->getSlotsCount();
        $emptySquadSlots = $squad->slots->slotEmpty();
        if ($emptySquadSlots->count() >= $slotsNeeded) {
            // Squad
            $slotsToFill = $emptySquadSlots->take($slotsNeeded);
            $item->slots()->saveMany($slotsToFill);
        } elseif ($this->storeHouseHasEnoughSlots($squad, $slotsNeeded)) {
            // StoreHouse
            $localStoreHouse = $squad->getLocalStoreHouse();
            $emptyStoreHouseSlots = $localStoreHouse->slots->slotEmpty()->take($slotsNeeded);
            $item->slots()->saveMany($emptyStoreHouseSlots);
        } else {
            // Stash
            $stash = $squad->getLocalStash();
            $slotsToFill = $stash->getEmptySlots($slotsNeeded);
            $item->slots()->saveMany($slotsToFill);
        }
    }

    /**
     * @param Squad $squad
     * @param int $slotsNeeded
     * @return bool
     */
    protected function storeHouseHasEnoughSlots(Squad $squad, int $slotsNeeded)
    {
        $localStoreHouse = $squad->getLocalStoreHouse();
        if (! $localStoreHouse) {
            return false;
        }
        $emptyStoreHouseSlots = $localStoreHouse->slots->slotEmpty();
        if ($emptyStoreHouseSlots->count() < $slotsNeeded) {
            return false;
        }
        return true;
    }
}
