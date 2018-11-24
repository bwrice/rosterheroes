<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Continent
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class Continent extends Model
{
    const FETROYA = 'Fetroya';
    const EAST_WOZUL = 'East Wozul';
    const WEST_WOZUL = 'West Wozul';
    const NORTH_JAGONETH = 'North Jagoneth';
    const CENTRAL_JAGONETH = 'Central Jagoneth';
    const SOUTH_JAGONETH = 'South Jagoneth';
    const VINDOBERON = 'Vindoberon';
    const DEMAUXOR = 'Demauxor';

    protected $guarded = [];
}
