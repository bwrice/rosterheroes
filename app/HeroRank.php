<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeroRank extends Model
{
    const PRIVATE = 'private';
    const CORPORAL = 'corporal';
    const SERGEANT = 'sergeant';

    protected $guarded = [];
}
