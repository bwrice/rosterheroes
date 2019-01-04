<?php

namespace App;

use App\Slots\HasSlots;
use App\Slots\Slot;
use App\Slots\SlotCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreHouse
 * @package App
 *
 * @property int $id
 *
 * @property Squad $squad
 * @property Province $province
 */
class StoreHouse extends Model implements HasSlots
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
        return $this->hasMany(Slot::class);
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        // TODO: Implement getEmptySlots() method.
    }

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return $this->squad->getLocalStash();
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
        // TODO: Implement getSlots() method.
    }
}
