<?php

namespace App\Domain\Models;

use App\Domain\Collections\GameCollection;
use App\Domain\Collections\PlayerCollection;
use App\Domain\Collections\TeamCollection;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\TeamQueryBuilder;
use App\Domain\Models\ExternalTeam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Team
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $abbreviation
 * @property string $external_id
 * @property int $league_id
 *
 * @property League $league
 *
 * @property PlayerCollection $players
 * @property GameCollection $homeGames
 * @property GameCollection $awayGames
 * @property Collection $externalTeams
 *
 * @method static TeamQueryBuilder query()
 */
class Team extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new TeamCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new TeamQueryBuilder($query);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function homeGames()
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames()
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    public function externalTeams()
    {
        return $this->hasMany(ExternalTeam::class);
    }

    /**
     * @return GameCollection
     */
    public function allGames()
    {
        return $this->homeGames->merge($this->awayGames);
    }

}
