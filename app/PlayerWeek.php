<?php

namespace App;

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
 */
class PlayerWeek extends EventSourcedModel
{
    const MIN_SALARY = 3000;

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function gameHasStarted()
    {
    }
}
