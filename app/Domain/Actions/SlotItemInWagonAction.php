<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SlotCollection;
use App\Domain\Collections\SlotTransactionCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Support\SlotTransaction;
use Illuminate\Support\Collection;

class SlotItemInWagonAction
{
    /** @var Squad */
    protected $squad;

    /** @var Item */
    protected $item;

    /** @var SlotTransactionCollection */
    protected $slotTransactions;

    public function execute(Squad $squad, Item $item, SlotTransactionCollection $slotTransactions = null)
    {
        $this->setProps($squad, $item, $slotTransactions);

        if ($this->slotInWagon()) {
            return $this->slotTransactions;
        }

        if ($this->slotInStoreHouse()) {
            return $this->slotTransactions;
        }

        $this->slotInStash();
        return $this->slotTransactions;
    }

    /**
     * @param Squad $squad
     * @param Item $item
     * @param SlotTransactionCollection $slotTransactions
     */
    protected function setProps(Squad $squad, Item $item, SlotTransactionCollection $slotTransactions = null)
    {
        $this->squad = $squad;
        $this->item = $item;
        $this->slotTransactions = $slotTransactions ?: new SlotTransactionCollection();
    }

    protected function addTransaction(HasSlots $hasSlots, SlotCollection $slotsToFill)
    {
        $this->item->slots()->saveMany($slotsToFill);
        $transaction = new SlotTransaction($slotsToFill->fresh(), $hasSlots, $this->item->fresh(), SlotTransaction::TYPE_FILL);
        $this->slotTransactions->push($transaction);
    }

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
        $this->addTransaction($this->squad, $slotsToFill);
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
        $this->addTransaction($localStoreHouse, $slotsToFill);
        return true;
    }

    protected function slotInStash()
    {
        $stash = $this->squad->getLocalStash();
        $slotsNeeded = $this->item->getSlotsCount();
        $slotToFill = $stash->getEmptySlots($slotsNeeded);
        $this->addTransaction($stash, $slotToFill);
    }
}
