<?php

namespace App\Wagons;

use App\Slots\HasSlots;
use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Slots\Slottable;
use App\Slots\SlottableCollection;
use App\SlotType;
use App\Squad;
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
class Wagon extends Model implements HasSlots
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
     * @return SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        return $this->slots->slotEmpty()->withSlotTypes($slotTypeIDs);
    }

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        // TODO: Implement getBackupHasSlots() method.
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
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlottableCollection
     */
    public function emptySlots(int $count, array $slotTypeIDs = []): SlottableCollection
    {
        return $this->slots->emptySlots($count, $slotTypeIDs);
    }

    /**
     * @return SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }
}
