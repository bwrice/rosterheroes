<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SlotCollection;
use App\Domain\Collections\SlotTransactionCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Support\SlotTransaction;
use App\Domain\Support\ItemTransactionGroup;
use Illuminate\Support\Collection;

class SlotItemInWagonAction
{
    /** @var Squad */
    protected $squad;

    /** @var Item */
    protected $item;

    /** @var ItemTransactionGroup */
    protected $itemTransactionGroup;

    public function execute(Squad $squad, Item $item, ItemTransactionGroup $itemTransactionGroup = null)
    {
        $this->setProps($squad, $item, $itemTransactionGroup);

        if ($this->slotInWagon()) {
            return $this->itemTransactionGroup;
        }

        if ($this->slotInStoreHouse()) {
            return $this->itemTransactionGroup;
        }

        $this->slotInStash();
        return $this->itemTransactionGroup;
    }

    /**
     * @param Squad $squad
     * @param Item $item
     * @param ItemTransactionGroup|null $itemTransactionGroup
     */
    protected function setProps(Squad $squad, Item $item, ItemTransactionGroup $itemTransactionGroup = null)
    {
        $this->squad = $squad;
        $this->item = $item;
        $this->itemTransactionGroup = $itemTransactionGroup ?: new ItemTransactionGroup();
    }
//
//    protected function addTransaction(HasSlots $hasSlots, SlotCollection $slotsToFill)
//    {
//        $this->item->slots()->saveMany($slotsToFill);
//        $transaction = new SlotTransaction($slotsToFill->fresh(), $hasSlots, $this->item->fresh(), SlotTransaction::TYPE_FILL);
//        $this->slotTransactionGroup->push($transaction);
//    }

    /**
     * @return bool
     */
    protected function slotInWagon()
    {
        $slotsNeeded = $this->item->getSlotsCount();
        $emptySquadSlots = $this->squad->slots->slotEmpty();
        if ($emptySquadSlots->count() < $slotsNeeded) {
            return false;
        }
        $slotsToFill = $emptySquadSlots->take($slotsNeeded);
        $this->item->slots()->saveMany($slotsToFill);
        $this->itemTransactionGroup->setSquad($this->squad);
        return true;
    }

    /**
     * @return bool
     */
    protected function slotInStoreHouse()
    {
        $localStoreHouse = $this->squad->getLocalStoreHouse();
        if (! $localStoreHouse) {
            return false;
        }
        $slotsNeeded = $this->item->getSlotsCount();
        $emptyStoreHouseSlots = $localStoreHouse->slots->slotEmpty();
        if ($emptyStoreHouseSlots->count() < $slotsNeeded) {
            return false;
        }
        $slotsToFill = $emptyStoreHouseSlots->take($slotsNeeded);
        $this->item->slots()->saveMany($slotsToFill);
        $this->itemTransactionGroup->setStoreHouse($localStoreHouse);
        return true;
    }

    protected function slotInStash()
    {
        $stash = $this->squad->getLocalStash();
        $slotsNeeded = $this->item->getSlotsCount();
        $slotsToFill = $stash->getEmptySlots($slotsNeeded);
        $this->item->slots()->saveMany($slotsToFill);
        $this->itemTransactionGroup->setStash($stash);
    }
}
