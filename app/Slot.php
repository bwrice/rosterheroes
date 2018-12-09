<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slot
 * @package App
 *
 * @property int $id
 * @property int $slot_type_id
 *
 * @property Collection $items
 */
class Slot extends Model
{
    protected $guarded = [];

    public function slotType()
    {
        return $this->belongsTo(SlotType::class);
    }

    public function items()
    {
        return $this->morphTo(Item::class);
    }
}
