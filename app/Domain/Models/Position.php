<?php

namespace App\Domain\Models;

use App\Domain\Models\Player;
use App\Domain\Models\HeroRace;
use App\Domain\Models\League;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Sport;
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
