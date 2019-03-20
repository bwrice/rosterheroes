<?php

namespace App;

use App\Domain\Players\Player;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerWeek
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $initial_salary
 * @property int $salary
 *
 * @property Player $player
 * @property Game $game
 */
class GamePlayer extends EventSourcedModel
{
    const MIN_SALARY = 3000;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function getPositions()
    {
        return $this->player->positions;
    }
}
