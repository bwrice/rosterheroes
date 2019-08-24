<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Province;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Domain\Models\Slot;
use App\Domain\Collections\SlotCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Stash
 * @package App
 *
 * @property SlotCollection $slots
 */
class Stash extends Model implements HasSlots
{
    protected $guarded = [];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function slots()
    {
        return $this->morphMany(Slot::class, 'has_slots');
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        $slotTypeIDs = $slotTypeIDs ?: SlotType::where('name', '=', SlotType::UNIVERSAL)->pluck('id')->toArray();
        $emptySlots = $this->slots->slotEmpty()->withSlotTypes($slotTypeIDs);

        $diff = $count - $emptySlots->count();
        if ($diff > 0) {
            $newSlots = $this->createEmptySlots($diff, $slotTypeIDs);
            $emptySlots = $emptySlots->merge($newSlots);
        }
        return $emptySlots;
    }

    public function createEmptySlots(int $count, array $slotTypeIDs): SlotCollection
    {
        $slotCollection = new SlotCollection();

        if ($count > 0 && $slotTypeIDs) {
            $slotTypeID = $this->getPreferredSlotTypeID($slotTypeIDs);

            for ($i = 1; $i <= $count; $i++) {
                $slotCollection = $slotCollection->push($this->slots()->create([
                    'slot_type_id' => $slotTypeID
                ]));
            }
        }

        return $slotCollection;
    }

    /**
     * @param array $slotTypeIDs
     * @return int
     */
    public function getPreferredSlotTypeID(array $slotTypeIDs)
    {
        $universalSlotTypeID = SlotType::where('name', '=', SlotType::UNIVERSAL)->first()->id;
        if($slotTypeIDs) {
            return in_array($universalSlotTypeID, $slotTypeIDs) ? $universalSlotTypeID : array_values($slotTypeIDs)[0];
        } else {
            return $universalSlotTypeID;
        }
    }


    /**
     * @return \App\Domain\Interfaces\HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return null;
    }

    /**
     * @param array $with
     * @return \App\Domain\Interfaces\HasSlots
     */
    public function getFresh($with = []): HasSlots
    {
        return $this->fresh($with);
    }

    /**
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }
}
