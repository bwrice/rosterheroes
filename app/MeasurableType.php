<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasurableType extends Model
{

    const STRENGTH = 'strength';
    const VALOR = 'valor';
    const AGILITY = 'agility';
    const FOCUS = 'focus';
    const APTITUDE = 'aptitude';
    const INTELLIGENCE = 'intelligence';

    const HEALTH = 'health';
    const MANA = 'mana';
    const STAMINA = 'stamina';

    const PASSION = 'passion';
    const BALANCE = 'balance';
    const HONOR = 'honor';
    const PRESTIGE = 'prestige';
    const WRATH = 'wrath';

    protected $guarded = [];
}
