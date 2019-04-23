<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GamePlayer
 * @package App\Domain\Models
 *
 * @property int $id
 *
 * @property Game $game
 * @property Player $player
 */
class GamePlayer extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
