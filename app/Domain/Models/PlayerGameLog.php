<?php

namespace App\Domain\Models;

use App\Domain\Collections\PlayerGameLogCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GamePlayer
 * @package App\Domain\Models
 *
 * @property int $id
 *
 * @property Game $game
 * @property Player $player
 * @property Team $team
 *
 * @property Collection $stats
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

    public function stats()
    {
        return $this->hasMany(PlayerStat::class);
    }
}
