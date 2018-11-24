<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeroClass extends Model
{
    const WARRIOR = 'warrior';
    const SORCERER = 'sorcerer';
    const RANGER = 'ranger';

    protected $guarded = [];
}
