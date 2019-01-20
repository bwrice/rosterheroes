<?php

namespace App;

use App\Weeks\Week;
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
 *
 * @property Sport $sport
 */
class Team extends Model
{
    protected $guarded = [];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function homeGames()
    {
        return $this->belongsToMany(Game::class, 'game_team', 'home_team_id')->withTimestamps();
    }

    public function awayGames()
    {
        return $this->belongsToMany(Game::class, 'game_team', 'away_team_id')->withTimestamps();
    }

    public function getThisWeeksGame()
    {
        return Week::current()->games()->whereHas('homeTeam', function (Builder $builder) {
            return $builder->where('id', '=', $this->id);
        })->orWhereHas('awayTeam', function (Builder $builder) {
            return $builder->where('id', '=', $this->id);
        })->first();
    }
}
