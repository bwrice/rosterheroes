<?php

namespace App\Wagons;

use App\Slot;
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
 */
class Wagon extends Model
{
    public function build(Squad $squad)
    {
        $this->wagon_size_id = WagonSize::getStarting()->id;
        $this->squad_id = $squad->id;
        $this->save();
        $this->buildSlots();
        return $this;
    }

    protected function buildSlots()
    {
        $slotsCount = $this->wagonSize->getBehavior()->getTotalSlotsCount();
        $slotType = SlotType::where('name', '=', SlotType::WAGON)->first();

        for($i=1; $i <= $slotsCount; $i++) {
            $this->slots()->create([
                'slot_type_id' => $slotType->id
            ]);
        }
    }

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
}
