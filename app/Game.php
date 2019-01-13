<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 * @package App
 *
 * @property int $id
 *
 * @property Team $homeTeam
 * @property Team $awayTeam
 */
class Game extends Model
{
    protected $guarded = [];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
