<?php

namespace App\Domain\Models;

use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\Slottable;
use App\Domain\Models\Item;
use App\Domain\Models\SlotType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int|null $item_id
 * @property int $slot_type_id
 * @property int $has_slots_id
 * @property string $has_slots_type
 *
 * @property Item|null $item
 * @property SlotType $slotType
 * @property HasSlots $hasSlots
 */
class Slot extends EventSourcedModel
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

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
