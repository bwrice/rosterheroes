<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sport
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class Sport extends Model
{
    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
