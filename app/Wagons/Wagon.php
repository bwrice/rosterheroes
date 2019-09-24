<?php

namespace App\Wagons;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use App\Domain\Collections\SlottableCollection;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Wagons\WagonSizes\WagonSize;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Wagon
 * @package App
 *
 * @property int $id
 * @property int $squad_id
 * @property int $wagon_size_id
 *
 * @property Squad $squad
 * @property WagonSize $wagonSize
 * @property SlotCollection $slots
 */
//TODO Delete?
class Wagon extends Model
{
    const RELATION_MORPH_MAP_KEY = 'wagons';

    protected $guarded = [];

//    public function build(Squad $squad)
//    {
//        $this->wagon_size_id = WagonSize::getStarting()->id;
//        $this->squad_id = $squad->id;
//        $this->save();
//        $this->buildSlots();
//        return $this;
//    }
//
//    protected function buildSlots()
//    {
//        $slotsCount = $this->wagonSize->getBehavior()->getTotalSlotsCount();
//        $slotType = SlotType::where('name', '=', SlotType::WAGON)->first();
//
//        for($i=1; $i <= $slotsCount; $i++) {
//            $this->slots()->create([
//                'slot_type_id' => $slotType->id
//            ]);
//        }
//    }

    public function slots()
    {
        return $this->morphMany(Slot::class, 'has_slots');
    }

    public function wagonSize()
    {
        return $this->belongsTo(WagonSize::class);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function equip(Slottable $slottable)
    {

    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        return $this->slots->slotEmpty()->withSlotTypes($slotTypeIDs);
    }

    /**
     * @return \App\Domain\Interfaces\HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        $storeHouse = $this->squad->getLocalStoreHouse();

        return $storeHouse ?: $this->squad->getLocalStash();
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
     * @param int $count
     * @param array $slotTypeIDs
     * @return \App\Domain\Collections\SlottableCollection
     */
    public function emptySlots(int $count, array $slotTypeIDs = []): SlottableCollection
    {
        return $this->slots->emptySlots($count, $slotTypeIDs);
    }

    /**
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }
}
