<?php

namespace App;

use App\Slots\HasSlots;
use App\Slots\Slot;
use App\Slots\SlotCollection;
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
     * @return SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        //TODO create slots as needed
    }

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return null;
    }

    /**
     * @param array $with
     * @return HasSlots
     */
    public function getFresh($with = []): HasSlots
    {
        return $this->fresh($with);
    }

    /**
     * @return SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }
}
