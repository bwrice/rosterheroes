<?php

namespace App;

use App\Slots\HasSlots;
use App\Slots\SlotCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Stash
 * @package App
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
        // TODO: Implement getSlots() method.
    }
}
