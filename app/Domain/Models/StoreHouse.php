<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Slot;
use App\Domain\Collections\SlotCollection;
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
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        // TODO: Implement getEmptySlots() method.
    }

    /**
     * @return \App\Domain\Interfaces\HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return $this->squad->getLocalStash();
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
        // TODO: Implement getSlots() method.
    }
}
