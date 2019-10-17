<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Minion extends Model
{
    public function attacks()
    {
        return $this->belongsToMany(Attack::class);
    }
}
