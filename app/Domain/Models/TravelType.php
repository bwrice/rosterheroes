<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\TravelTypes\BorderTravelBehavior;
use App\Domain\Behaviors\TravelTypes\ContinentTravelBehavior;
use App\Domain\Behaviors\TravelTypes\RealmTravelBehavior;
use App\Domain\Behaviors\TravelTypes\StationaryTravelBehavior;
use App\Domain\Behaviors\TravelTypes\TerritoryTravelBehavior;
use App\Domain\Behaviors\TravelTypes\TravelTypeBehavior;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TravelType
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
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

    public function getBehavior(): TravelTypeBehavior
    {
        switch ($this->name) {
            case self::STATIONARY:
                return app(StationaryTravelBehavior::class);
            case self::BORDER:
                return app(BorderTravelBehavior::class);
            case self::TERRITORY:
                return app(TerritoryTravelBehavior::class);
            case self::CONTINENT:
                return app(ContinentTravelBehavior::class);
            case self::REALM:
                return app(RealmTravelBehavior::class);
        }
        throw new UnknownBehaviorException($this->name, TravelTypeBehavior::class);
    }
}
