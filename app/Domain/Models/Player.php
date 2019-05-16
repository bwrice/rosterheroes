<?php

namespace App\Domain\Models;

use App\Domain\Models\Game;
use App\Domain\Models\Position;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Team;
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
 * @property string $external_id
 *
 * @property \App\Domain\Models\Team $team
 * @property \App\Domain\Collections\PositionCollection $positions
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

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isOnTeam(Team $team)
    {
        return $this->team->id === $team->id;
    }
}
