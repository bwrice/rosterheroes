<?php

namespace App;

use App\Weeks\Week;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Campaign
 * @package App
 *
 * @property int $id
 * @property int $week_id
 * @property int $squad_id
 * @property int $continent_id
 *
 * @property Squad $squad
 * @property Week $week
 * @property Continent $continent
 *
 * @method static Builder squadThisWeek(int $squadID)
 */
class Campaign extends Model
{
    protected $guarded = [];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    public function scopeSquadThisWeek(Builder $builder, int $squadID)
    {
        return $builder->where('squad_id', '=', $squadID)->where('week_id', '=', Week::current()->id);
    }
}
