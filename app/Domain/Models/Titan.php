<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Titan extends Model
{
    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class);
    }
}
