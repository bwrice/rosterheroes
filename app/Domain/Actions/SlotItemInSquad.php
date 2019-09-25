<?php


namespace App\Domain\Actions;


use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use Illuminate\Support\Collection;

class SlotItemInSquad
{
    public function execute(Squad $squad, Item $item, Collection $slotTransactions = null)
    {
        $slotTransactions = $slotTransactions ?: collect();
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
        return $slotTransactions;
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
