<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreHouse
 * @package App
 *
 * @property int $id
 *
 * @property Squad $squad
 * @property Province $province
 */
class StoreHouse extends Model
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
