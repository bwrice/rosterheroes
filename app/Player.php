<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Player
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property Collection $games
 * @property Collection $positions
 */
class Player extends Model
{
    protected $guarded = [];

    public function games()
    {
        return $this->belongsToMany(Game::class)->withTimestamps();
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class)->withTimestamps();
    }

    public function getThisWeeksGame()
    {
        $now = Carbon::now();
    }
}
