<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/8/18
 * Time: 9:46 PM
 */

namespace App\Domain\Collections;

use App\Domain\Slot;
use App\Domain\Collections\SlottableCollection;
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
        $this->loadMissing('slottable')->each(function (Slot $slot) use ($slottableCollection) {
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

    /**
     * @return SlotCollection
     */
    public function emptySlottables()
    {
        return $this->each(function (Slot $slot) {
            $slot->slottable_id = null;
            $slot->slottable_type = null;
            $slot->save();
        });
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     *
     * @return SlottableCollection
     *
     * @throws \RuntimeException
     *
     * Takes $count number of slots with slot_type_id in array $slotTypeIDs, empties them from slots
     * and returns a SlottableCollection of the slottables that were emptied
     */
    public function emptySlots(int $count = null, array $slotTypeIDs = [])
    {

        if ( $slotTypeIDs ) {
            $slotsToEmpty = $this->filter(function(Slot $slot) use ($slotTypeIDs) {
                return in_array( $slot->slot_type_id, $slotTypeIDs );
            });
        } else {
            $slotsToEmpty = $this;
        }

        //Lazy load the slottables and filter Slots by those with a slottable
        $slotsToEmpty = $slotsToEmpty->loadMissing('slottable')->slotFilled();

        if ( is_integer($count) ) {

            if ( $count > $slotsToEmpty->count() ) {
                throw new \RuntimeException("Not enough available filled slots to empty");
            }

            $slotsToEmpty = $slotsToEmpty->take($count);
        }

//        $slottables = new SlottableCollection();
//        $slotsToEmpty->each(function (Slot $slot) use ($slottables) {
//            $slottables->push($slot->slottable);
//            $slot->slottable_id = null;
//            $slot->slottable_type = null;
//            $slot->save();
//        });

        $slottableArray = $slotsToEmpty->map(function (Slot $slot) {
            return $slot->slottable;
            //filter nulls
        })->filter()->values()->unique()->toArray();

        $slottables = new SlottableCollection($slottableArray);

        return $slottables->removeFromSlots();
    }
}