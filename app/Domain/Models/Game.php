<?php

namespace App\Domain\Models;

use App\Domain\Collections\GameCollection;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\GameQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 * @package App
 *
 * @property int $id
 * @property string $external_id
 * @property Carbon $starts_at
 *
 * @property \App\Domain\Models\Team $homeTeam
 * @property \App\Domain\Models\Team $awayTeam
 *
 * @method static GameQueryBuilder query()
 */
class Game extends Model
{
    protected $guarded = [];

    protected $dates = [
        'starts_at',
        'created_at',
        'updated_at'
    ];

    public function newCollection(array $models = [])
    {
        return new GameCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new GameQueryBuilder($query);
    }

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
        return $this->awayTeam->name . ' @ ' . $this->homeTeam->name;
    }

    public function hasTeam(Team $team)
    {
        return $this->homeTeam->id === $team->id || $this->awayTeam->id === $team->id;
    }

    public function playerGameLogs()
    {
        return $this->hasMany(PlayerGameLog::class);
    }
}
