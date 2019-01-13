<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 * @package App
 *
 * @property int $id
 * @property Carbon $starts_at
 *
 * @property Team $homeTeam
 * @property Team $awayTeam
 */
class Game extends Model
{
    protected $guarded = [];

    protected $dates = [
        'starts_at',
        'created_at',
        'updated_at'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
