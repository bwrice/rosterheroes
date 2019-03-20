<?php

namespace App;

use App\Positions\Position;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sport
 * @package App
 *
 * @property string $name
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
