<?php

namespace App\Domain\Models;

use App\Domain\Models\Traits\HasUniqueNames;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TravelType
 * @package App\Domain\Models
 *
 * @property int $id
 */
class TravelType extends Model
{
    use HasUniqueNames;

    protected $guarded = [];

    public const STATIONARY = 'Stationary';
    public const BORDER = 'Border';
    public const TERRITORY = 'Territory';
    public const CONTINENT = 'Continent';
    public const REALM = 'Realm';
}
