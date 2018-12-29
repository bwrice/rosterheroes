<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enchantment extends Model
{
    const RELATION_MORPH_MAP_KEY = 'enchantments';

    protected $guarded = [];

    public function measurableBoosts()
    {
        return $this->morphMany(MeasurableBoost::class, 'booster' );
    }
}
