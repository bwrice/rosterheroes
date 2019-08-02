<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Continents\ContinentBehavior;
use App\Domain\Traits\HasSlug;
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
    use HasSlug;

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
        switch($this->name) {
            case self::FETROYA:
                return new ContinentBehavior('#ccc802');
            case self::EAST_WOZUL:
                return new ContinentBehavior('#d18c02');
            case self::WEST_WOZUL:
                return new ContinentBehavior('#c12907');
            case self::NORTH_JAGONETH:
                return new ContinentBehavior('#46a040');
            case self::CENTRAL_JAGONETH:
                return new ContinentBehavior('#3e81a5');
            case self::SOUTH_JAGONETH:
                return new ContinentBehavior('#6834aa');
            case self::VINDOBERON:
                return new ContinentBehavior('#4f547a');
            case self::DEMAUXOR:
                return new ContinentBehavior('#9e1284');
        }
        throw new UnknownBehaviorException((string)$this->name, ContinentBehavior::class);
    }

    public function realmColor()
    {
        return $this->getBehavior()->getRealmColor();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
