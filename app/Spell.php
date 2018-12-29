<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spell extends Model
{
    const RELATION_MORPH_MAP_KEY = 'spells';

    protected $guarded = [];

    public function measurableBoosts()
    {
        return $this->morphMany(MeasurableBoost::class, 'booster' );
    }
}
