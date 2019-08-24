<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/8/18
 * Time: 9:46 PM
 */

namespace App\Domain\Collections;

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
    public function emptySlottables()
    {
        return $this->each(function (Slot $slot) {
            $slot->item_id = null;
            $slot->save();
        });
    }
}
