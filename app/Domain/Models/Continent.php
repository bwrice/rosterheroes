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
                        'pan_y' => 18,
                        'zoom_x' => 130,
                        'zoom_y' => 99
                    ]));
            case self::EAST_WOZUL:
                return new ContinentBehavior(
                    new RealmBehavior('#d18c02', [
                        'pan_x' => 185,
                        'pan_y' => 70,
                        'zoom_x' => 130,
                        'zoom_y' => 99
                    ]));
            case self::WEST_WOZUL:
                return new ContinentBehavior(
                    new RealmBehavior('#c12907', [
                        'pan_x' => 135,
                        'pan_y' => 99,
                        'zoom_x' => 150,
                        'zoom_y' => 114
                    ]));
            case self::NORTH_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#46a040', [
                        'pan_x' => 60,
                        'pan_y' => 3,
                        'zoom_x' => 160,
                        'zoom_y' => 122
                    ]));
            case self::CENTRAL_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#3e81a5', [
                        'pan_x' => 48,
                        'pan_y' => 48,
                        'zoom_x' => 130,
                        'zoom_y' => 99
                    ]));
            case self::SOUTH_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#6834aa', [
                        'pan_x' => 24,
                        'pan_y' => 74,
                        'zoom_x' => 172,
                        'zoom_y' => 131
                    ]));
            case self::VINDOBERON:
                return new ContinentBehavior(
                    new RealmBehavior('#4f547a', [
                        'pan_x' => -48,
                        'pan_y' => 8,
                        'zoom_x' => 184,
                        'zoom_y' => 141
                    ]));
            case self::DEMAUXOR:
                return new ContinentBehavior(
                    new RealmBehavior('#9e1284', [
                        'pan_x' => 0,
                        'pan_y' => 126,
                        'zoom_x' => 160,
                        'zoom_y' => 121
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
