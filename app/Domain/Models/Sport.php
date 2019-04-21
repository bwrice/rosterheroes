<?php

namespace App\Domain\Models;

use App\Domain\Collections\PositionCollection;
use App\Domain\Models\League;
use App\Domain\Models\Position;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sport
 * @package App
 *
 * @property string $name
 *
 * @property PositionCollection $positions
 */
class Sport extends Model
{
    protected $guarded = [];

    const FOOTBALL = 'football';
    const BASKETBALL = 'basketball';
    const HOCKEY = 'hockey';
    const BASEBALL = 'baseball';

    public function leagues()
    {
        return $this->hasMany(League::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
