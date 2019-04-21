<?php

namespace App\Domain\Models;

use App\Domain\Collections\TeamCollection;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $abbreviation
 * @property string $external_id
 *
 * @property League $league
 */
class Team extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new TeamCollection($models);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function homeGames()
    {
        return $this->belongsToMany(Game::class, 'game_team', 'home_team_id')->withTimestamps();
    }

    public function awayGames()
    {
        return $this->belongsToMany(Game::class, 'game_team', 'away_team_id')->withTimestamps();
    }

    public function thisWeeksGame()
    {
        return Week::current()->games()->where(function (Builder $builder) {
            $builder->whereHas('homeTeam', function (Builder $builder) {
                return $builder->where('id', '=', $this->id);
            })->orWhereHas('awayTeam', function (Builder $builder) {
                return $builder->where('id', '=', $this->id);
            });
        })->first();
    }
}
