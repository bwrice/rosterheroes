<?php

namespace App\Domain\Models;

use App\Domain\Collections\PlayerGameLogCollection;
use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\Collections\PlayerStatCollection;
use App\Domain\QueryBuilders\PlayerGameLogQueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GamePlayer
 * @package App\Domain\Models
 *
 * @property int $id
 * @property int $team_id
 * @property int $player_id
 * @property int $game_id
 *
 * @property Game $game
 * @property Player $player
 * @property Team $team
 * @property PlayerSpirit $playerSpirit
 *
 * @property PlayerStatCollection $playerStats
 */
class PlayerGameLog extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new PlayerGameLogCollection($models);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function playerSpirit()
    {
        return $this->hasOne(PlayerSpirit::class);
    }

    public function newEloquentBuilder($query)
    {
        return new PlayerGameLogQueryBuilder($query);
    }

    public function playerStats()
    {
        return $this->hasMany(PlayerStat::class);
    }

    public function getDescription()
    {
        $playerName = $this->player->fullName();
        $teamAbbreviation = $this->team->abbreviation;
        $gameDesc = $this->game->getSimpleDescription();
        return $playerName . ' (' . $teamAbbreviation . ') ' . $gameDesc;
    }
}
