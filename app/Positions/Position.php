<?php

namespace App\Positions;

use App\Domain\Players\Player;
use App\HeroRace;
use App\League;
use App\Sport;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Position
 * @package App
 *
 * @property string $name
 * @property int $sport_id
 *
 * @property Sport $sport
 */
class Position extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new PositionCollection($models);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->withTimestamps();
    }

    public function heroRaces()
    {
        return $this->belongsToMany(HeroRace::class)->withTimestamps();
    }
}
