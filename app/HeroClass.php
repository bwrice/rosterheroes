<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroClass
 * @package App
 *
 * @property int $id
 */
class HeroClass extends Model
{
    const WARRIOR = 'warrior';
    const SORCERER = 'sorcerer';
    const RANGER = 'ranger';

    protected $guarded = [];
}
