<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Titan extends Model
{
    public function attacks()
    {
        return $this->belongsToMany(Attack::class);
    }
}
