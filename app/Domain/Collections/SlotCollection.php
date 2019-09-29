<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/8/18
 * Time: 9:46 PM
 */

namespace App\Domain\Collections;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use Illuminate\Database\Eloquent\Collection;

class SlotCollection extends Collection
{
    public function slotFilled()
    {
        return $this->filter(function (Slot $slot) {
            return $slot->item_id != null;
        });
    }

    public function slotEmpty()
    {
        return $this->filter(function (Slot $slot) {
            return $slot->item_id == null;
        });
    }

    public function withSlotTypes(array $slotTypeIDs)
    {
        return $this->filter(function (Slot $slot) use ($slotTypeIDs) {
            return in_array($slot->slot_type_id, $slotTypeIDs);
        });
    }

    /**
     * @return ItemCollection
     */
    public function getItems(): ItemCollection
    {
        $items = new ItemCollection();
        $this->loadMissing('item')->each(function (Slot $slot) use ($items) {
           $item = $slot->item;
           if ($item) {
               $items->push($item);
           }
        });
        return $items->unique();
    }

    /**
     * @return array
     */
    public function getIDs()
    {
        return $this->pluck('id')->toArray();
    }

    /**
     * @return SlotCollection
     */
    public function emptyItems()
    {
        return $this->each(function (Slot $slot) {
            $slot->item_id = null;
            $slot->save();
        });
    }

    /**
     * @param Item $item
     * @return SlotCollection
     */
    public function filledWithItem(Item $item)
    {
        return $this->filter(function (Slot $slot) use ($item) {
            return $slot->item_id === $item->id;
        });
    }

    public function allBelongToHasSlots(HasSlots $hasSlots)
    {
        $nonMatch = $this->first(function (Slot $slot) use ($hasSlots) {
            return ! $slot->belongsToHasSlots($hasSlots);
        });
        return $nonMatch === null;
    }

    public function firstMatching(Slot $slotToFind)
    {
        return $this->first(function (Slot $slot) use ($slotToFind) {
            return $slot->id === $slotToFind->id;
        });
    }
}
