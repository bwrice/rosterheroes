<?php

namespace App\Slots;

use App\Slots\SlotCollection;
use App\Slots\Slottable;
use App\SlotType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slot
 * @package App
 *
 * @property int $id
 * @property int $slot_type_id
 * @property int $slottable_id
 * @property string $slottable_type
 * @property int $has_slots_id
 * @property string $has_slots_type
 *
 * @property Collection $items
 * @property Slottable $slottable
 * @property SlotType $slotType
 */
class Slot extends Model
{
    protected $guarded = [];

    public function newCollection(array $slots = [])
    {
        return new SlotCollection($slots);
    }

    public function slotType()
    {
        return $this->belongsTo(SlotType::class);
    }

    public function hasSlots()
    {
        return $this->morphTo();
    }

    public function slottable()
    {
        return $this->morphTo();
    }
}
