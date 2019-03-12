<?php

namespace App;

use App\Positions\Position;
use App\Positions\PositionCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Player
 * @package App
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 *
 * @property Team $team
 * @property PositionCollection $positions
 */
class Player extends Model
{
    protected $guarded = [];

    public function positions()
    {
        return $this->belongsToMany(Position::class)->withTimestamps();
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return Game|null
     */
    public function getThisWeeksGame()
    {
        return $this->team->thisWeeksGame();
    }

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
