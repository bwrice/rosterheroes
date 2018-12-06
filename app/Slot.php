<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Slot
 * @package App
 *
 * @property int $id
 * @property int $slot_type_id
 */
class Slot extends Model
{
    protected $guarded = [];

    public function slotType()
    {
        return $this->belongsTo(SlotType::class);
    }
}
