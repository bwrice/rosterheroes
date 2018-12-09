<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spell extends Model
{

    protected $guarded = [];

    public function measurableBoosts()
    {
        return $this->morphMany(MeasurableBoost::class, 'booster' );
    }
}
