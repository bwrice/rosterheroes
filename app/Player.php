<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Player
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class Player extends Model
{
    protected $guarded = [];

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}
