<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package App
 *
 * @property int $id
 *
 * @property Sport $sport
 */
class Team extends Model
{
    protected $guarded = [];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
