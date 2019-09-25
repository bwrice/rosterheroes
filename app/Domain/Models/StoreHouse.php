<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Collections\SlotCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class StoreHouse
 * @package App
 *
 * @property int $id
 * @property int $province_id
 *
 * @property Squad $squad
 * @property Province $province
 * @property StoreHouseType $storeHouseType
 *
 * @property SlotCollection $slots
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
        return $this->morphMany(Slot::class, 'has_slots');
    }

    public function storeHouseType()
    {
        return $this->belongsTo(StoreHouseType::class);
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

    public function getUniqueIdentifier(): string
    {
        // TODO: Implement getUniqueIdentifier() method.
    }

    public function addSlots()
    {
        // TODO: refactor this and similar method on Squad to use same logic
        $slotsNeededCount = $this->storeHouseType->getBehavior()->getGetTotalSlotsCount();
        $currentSlotsCount = $this->slots()->count();
        $diff = $slotsNeededCount - $currentSlotsCount;

        if($diff > 0) {
            /** @var SlotType $slotType */
            $slotType = SlotType::where('name', '=', SlotType::UNIVERSAL)->first();
            for($i = 1; $i <= $diff; $i++) {
                $this->slots()->create([
                    'uuid' => Str::uuid(),
                    'slot_type_id' => $slotType->id
                ]);
            }
        }
    }
}
