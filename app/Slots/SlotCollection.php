<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/8/18
 * Time: 9:46 PM
 */

namespace App\Slots;

use App\Slots\Slot;
use Illuminate\Database\Eloquent\Collection;

class SlotCollection extends Collection
{
    public function filled()
    {
        return $this->filter(function (Slot $slot) {
            return $slot->slottable_id != null;
        });
    }

    public function slotEmpty()
    {
        return $this->filter(function (Slot $slot) {
            return $slot->slottable_id == null;
        });
    }

    public function slotFilled()
    {
        return $this->filter(function (Slot $slot) {
           return $slot->slottable_id != null;
        });
    }

    public function withSlotTypes(array $slotTypeIDs)
    {
        return $this->filter(function (Slot $slot) use ($slotTypeIDs) {
            return in_array($slot->slot_type_id, $slotTypeIDs);
        });
    }

    /**
     * @return SlottableCollection
     */
    public function getSlottables()
    {
        $slottableCollection = new SlottableCollection();
        $this->each(function (Slot $slot) use ($slottableCollection) {
           $slottable = $slot->slottable;
           if ($slottable) {
               $slottableCollection->push($slottable);
           }
        });
        return $slottableCollection->unique();
    }

    /**
     * @return array
     */
    public function getIDs()
    {
        return $this->pluck('id')->toArray();
    }
}