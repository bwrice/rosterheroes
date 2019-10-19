<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class TravelType extends Model
{
    protected $guarded = [];

    public const STATIONARY = 'Stationary';
    public const BORDER = 'Border';
    public const TERRITORY = 'Territory';
    public const CONTINENT = 'Continent';
    public const REALM = 'Realm';
}
