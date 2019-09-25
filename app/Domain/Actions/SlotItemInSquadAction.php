<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Support\SlotTransaction;
use Illuminate\Support\Collection;

class SlotItemInSquad
{
    public function execute(Squad $squad, Item $item, Collection $slotTransactions = null)
    {
        $slotTransactions = $slotTransactions ?: collect();
        $slotsNeeded = $item->getSlotsCount();

        $squadSlots = $this->getSquadSlots($squad, $slotsNeeded);
        if ($squadSlots) {
            return $this->addTransaction($slotTransactions, $item, $squad, $squadSlots);
        }

        $storeHouseSlots = $this->getStoreHouseSlots($squad, $slotsNeeded);
        if ($storeHouseSlots) {
            return $this->addTransaction($slotTransactions, $item, $squad->getLocalStoreHouse(), $storeHouseSlots);
        }

        $stash = $squad->getLocalStash();
        $stashSlots = $stash->getEmptySlots($slotsNeeded);
        return $this->addTransaction($slotTransactions, $item, $stash, $stashSlots);
    }

    protected function addTransaction(Collection $slotTransactions, Item $item, HasSlots $hasSlots, SlotCollection $slotsToFill)
    {
        $item->slots()->saveMany($slotsToFill);
        $transaction = new SlotTransaction($slotsToFill->fresh(), $hasSlots, $item->fresh(), SlotTransaction::TYPE_FILL);
        $slotTransactions->push($transaction);
        return $slotTransactions;
    }

    /**
     * @param Squad $squad
     * @param int $slotsNeeded
     * @return SlotCollection|null
     */
    protected function getSquadSlots(Squad $squad, int $slotsNeeded)
    {
        $emptySquadSlots = $squad->slots->slotEmpty();
        if ($emptySquadSlots->count() < $slotsNeeded) {
            return null;
        }
        return $emptySquadSlots->take($slotsNeeded);
    }

    /**
     * @param Squad $squad
     * @param int $slotsNeeded
     * @return SlotCollection|null
     */
    protected function getStoreHouseSlots(Squad $squad, int $slotsNeeded)
    {
        $localStoreHouse = $squad->getLocalStoreHouse();
        if (! $localStoreHouse) {
            return null;
        }
        $emptyStoreHouseSlots = $localStoreHouse->slots->slotEmpty();
        if ($emptyStoreHouseSlots->count() < $slotsNeeded) {
            return null;
        }
        return $emptyStoreHouseSlots->take($slotsNeeded);
    }
}
