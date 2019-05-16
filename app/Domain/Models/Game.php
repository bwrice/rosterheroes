<?php

namespace App\Domain\Models;

use App\Domain\Models\Team;
use App\Domain\Models\Week;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 * @package App
 *
 * @property int $id
 * @property Carbon $starts_at
 *
 * @property \App\Domain\Models\Team $homeTeam
 * @property \App\Domain\Models\Team $awayTeam
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

    public function hasStarted()
    {
        return $this->starts_at->isPast();
    }

    public function getSimpleDescription()
    {
        return $this->awayTeam->name . ' at ' . $this->homeTeam->name;
    }

    public function hasTeam(Team $team)
    {
        return $this->homeTeam->id === $team->id || $this->awayTeam->id === $team->id;
    }
}
