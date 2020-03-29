<?php

namespace App\Domain\Models;

use App\Domain\Collections\GameCollection;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\GameQueryBuilder;
use App\Domain\Models\ExternalGame;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 * @package App
 *
 * @property int $id
 * @property int $home_team_id
 * @property int $away_team_id
 * @property string $external_id
 * @property string $schedule_status
 * @property CarbonImmutable $starts_at
 * @property CarbonImmutable|null $finalized_at
 *
 * @property \App\Domain\Models\Team $homeTeam
 * @property \App\Domain\Models\Team $awayTeam
 *
 * @method static GameQueryBuilder query()
 */
class Game extends Model
{
    public const SCHEDULE_STATUS_NORMAL = 'NORMAL';
    public const SCHEDULE_STATUS_DELAYED = 'DELAYED';

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

    public function playerSpirits()
    {
        return $this->hasMany(PlayerSpirit::class);
    }

    public function playerGameLogs()
    {
        return $this->hasMany(PlayerGameLog::class);
    }

    public function externalGames()
    {
        return $this->hasMany(ExternalGame::class);
    }
}
