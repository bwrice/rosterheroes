<?php

namespace App\Domain\Models;

use App\Domain\Collections\PlayerCollection;
use App\Domain\Collections\PlayerGameLogCollection;
use App\Domain\Models\Game;
use App\Domain\Models\Position;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Team;
use App\Domain\QueryBuilders\PlayerQueryBuilder;
use App\ExternalPlayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Player
 * @package App
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $external_id
 * @property int $team_id
 *
 * @property Team $team
 * @property PositionCollection $positions
 * @property PlayerGameLogCollection $playerGameLogs
 * @property Collection $externalPlayers
 *
 * @method static PlayerQueryBuilder query()
 */
class Player extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new PlayerCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new PlayerQueryBuilder($query);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class)->withTimestamps();
    }

    public function playerGameLogs()
    {
        return $this->hasMany(PlayerGameLog::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function externalPlayers()
    {
        return $this->hasMany(ExternalPlayer::class);
    }

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isOnTeam(Team $team)
    {
        return $this->team->id === $team->id;
    }
}
