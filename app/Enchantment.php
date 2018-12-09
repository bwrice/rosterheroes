<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enchantment extends Model
{
    protected $guarded = [];

    public function measurableBoosts()
    {
        return $this->morphMany(MeasurableBoost::class, 'booster' );
    }
}
