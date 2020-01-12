<?php

namespace App;

use App\Domain\Models\Hero;
use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroSnapshot
 * @package App
 *
 * @property Hero $hero
 */
class HeroSnapshot extends Model
{
    protected $guarded = [];
    protected $casts = [
        'data' => 'array'
    ];

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }
}
