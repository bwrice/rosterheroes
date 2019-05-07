<?php

namespace App\Domain\Models;

use App\Domain\Models\EventSourcedModel;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
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
 * @property Week $week
 * @property Player $player
 * @property Game $game
 */
class WeeklyGamePlayer extends EventSourcedModel
{
    const MIN_SALARY = 3000;

    public function gamePlayer()
    {
        return $this->belongsTo(PlayerGameLog::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getPositions()
    {
        return $this->player->positions;
    }
}
