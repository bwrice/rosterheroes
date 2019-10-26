<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Province;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Domain\Models\Slot;
use App\Domain\Collections\SlotCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Stash
 * @package App
 *
 * @property SlotCollection $slots
 */
class Stash extends Model
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
}
