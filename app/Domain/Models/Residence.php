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
 * @property ResidenceType $residenceType
 *
 * @property SlotCollection $slots
 */
class Residence extends Model
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

    public function residenceType()
    {
        return $this->belongsTo(ResidenceType::class);
    }
}
