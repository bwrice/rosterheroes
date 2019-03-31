<?php

namespace App\Domain\Models;

use App\Domain\Models\MeasurableBoost;
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
