<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Realm\Continents\ContinentBehavior;
use App\Domain\Behaviors\Realm\ContinentBehaviorFactory;
use App\Domain\Behaviors\Realm\RealmBehavior;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Domain\Traits\HasNameSlug;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Continent
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Continent extends Model
{
    use HasNameSlug;
    use HasUniqueNames;

    const FETROYA = 'Fetroya';
    const EAST_WOZUL = 'East Wozul';
    const WEST_WOZUL = 'West Wozul';
    const NORTH_JAGONETH = 'North Jagoneth';
    const CENTRAL_JAGONETH = 'Central Jagoneth';
    const SOUTH_JAGONETH = 'South Jagoneth';
    const VINDOBERON = 'Vindoberon';
    const DEMAUXOR = 'Demauxor';

    protected $guarded = [];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    public function getBehavior(): ContinentBehavior
    {
        /** @var ContinentBehaviorFactory $behaviorFactory */
        $behaviorFactory = app(ContinentBehaviorFactory::class);
        return $behaviorFactory->getBehavior($this->name);
    }

    public function realmColor()
    {
        return $this->getBehavior()->getRealmColor();
    }

    public function getViewBox()
    {
        return $this->getBehavior()->getViewBox();
    }
}
