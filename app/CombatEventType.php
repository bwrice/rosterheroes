<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CombatEventType extends Model
{
    public const ATTACK = 'attack';
    public const BLOCK = 'block';
    public const KILL = 'kill';
    
    protected $guarded = [];
}
