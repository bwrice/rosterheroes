<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Map\ContinentBehavior;
use App\Domain\Behaviors\Map\RealmBehavior;
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
        switch ($this->name) {
            case self::FETROYA:
                return new ContinentBehavior(
                    new RealmBehavior('#b2b800', [
                        'pan_x' => 178,
                        'pan_y' => 20,
                        'zoom_x' => 130,
                        'zoom_y' => 96
                    ]));
            case self::EAST_WOZUL:
                return new ContinentBehavior(
                    new RealmBehavior('#d18c02', [
                        'pan_x' => 185,
                        'pan_y' => 72,
                        'zoom_x' => 130,
                        'zoom_y' => 93
                    ]));
            case self::WEST_WOZUL:
                return new ContinentBehavior(
                    new RealmBehavior('#c12907', [
                        'pan_x' => 155,
                        'pan_y' => 100,
                        'zoom_x' => 120,
                        'zoom_y' => 110
                    ]));
            case self::NORTH_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#46a040', [
                        'pan_x' => 78,
                        'pan_y' => 3,
                        'zoom_x' => 132,
                        'zoom_y' => 122
                    ]));
            case self::CENTRAL_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#3e81a5', [
                        'pan_x' => 58,
                        'pan_y' => 48,
                        'zoom_x' => 113,
                        'zoom_y' => 98
                    ]));
            case self::SOUTH_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#6834aa', [
                        'pan_x' => 36,
                        'pan_y' => 74,
                        'zoom_x' => 150,
                        'zoom_y' => 130
                    ]));
            case self::VINDOBERON:
                return new ContinentBehavior(
                    new RealmBehavior('#4f547a', [
                        'pan_x' => 0,
                        'pan_y' => 10,
                        'zoom_x' => 100,
                        'zoom_y' => 138
                    ]));
            case self::DEMAUXOR:
                return new ContinentBehavior(
                    new RealmBehavior('#9e1284', [
                        'pan_x' => 0,
                        'pan_y' => 135,
                        'zoom_x' => 160,
                        'zoom_y' => 100
                    ]));
        }
        throw new UnknownBehaviorException((string)$this->name, ContinentBehavior::class);
    }

    public function realmColor()
    {
        return $this->getBehavior()->getRealmColor();
    }

    public function realmViewBox()
    {
        return $this->getBehavior()->getRealmViewBox();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
