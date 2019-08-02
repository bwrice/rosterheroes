<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Map\RealmBehavior;
use App\Domain\Behaviors\Map\TerritoryBehavior;
use App\Domain\Models\Continent;
use App\Domain\Traits\HasSlug;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Territory
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Territory extends Model
{
    use HasSlug;

    const GARDENS_OF_REDEMPTION = 'Gardens of Redemption';
    const WOODS_OF_THE_WILD = 'Woods of the Wild';
    const TWISTING_ISLES_OF_ILLUSIONS = 'Twisting Isles of Illusions';
    const GRASSLANDS_OF_GIANTS = 'Grasslands of Giants';
    const MENACING_MARCHES = 'Menacing Marshes';
    const TROPICS_OF_TREPIDATION = 'Tropics of Trepidation';
    const PERILOUS_PLANS = 'Perilous Plains';
    const TREACHEROUS_FOREST = 'Treacherous Forest';
    const SAVAGE_SWAMPS = 'Savage Swamps';
    const DESERT_OF_DESPAIR = 'Desert of Despair';
    const HUMBLING_HILLS = 'Humbling Hills';
    const GULF_OF_SERPENTS = 'Gulf of Serpents';
    const CARNIVOROUS_CANYONS = 'Carnivorous Canyons';
    const INFERNAL_ISLANDS = 'Infernal Islands';
    const JADE_JUNGLE = 'Jade Jungle';
    const SHORES_OF_THE_SHADOWS = 'Shores of the Shadows';
    const VALLEY_OF_VANISHINGS = 'Valley of Vanishings';
    const PRIMAL_PASSAGE = 'Primal Passage';
    const ARCTIC_ARCHIPELAGO = 'Arctic Archipelago';
    const BADLANDS_OF_THE_LEVIATHAN = 'Badlands of the Leviathan';
    const PEAKS_OF_PANDORA = 'Peaks of Pandora';
    const CRYPTIC_COAST = 'Cryptic Coast';
    const WETLANDS_OF_WANDERING_TORMENT = 'Wetlands of Wandering Torment';
    const BLOODSTONE_BEACHES = 'Bloodstone Beaches';
    const TURBULENT_TUNDRA = 'Turbulent Tundra';
    const COVES_OF_CORRUPTION = 'Coves of Corruption';
    const MOUNTAINS_OF_MISERY = 'Mountains of Misery';
    const ENCLAVE_OF_ENIGMAS = 'Enclave of Enigmas';
    const SCREAMING_HIGHLANDS_OF_NIGHTMARES = 'Screaming Highlands of Nightmares';
    const CLOUD_PIERCING_CLIFFS = 'Cloud Piercing Cliffs';
    const OUTER_RIM_OF_THE_DEMONIC = 'Outer Rim of the Demonic';

    protected $guarded = [];

    public function continents()
    {
        return $this->belongsToMany(Continent::class)->withTimestamps();
    }

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getBehavior(): TerritoryBehavior
    {
        switch ($this->name) {
            case self::GARDENS_OF_REDEMPTION:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#a05858',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::WOODS_OF_THE_WILD:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#579368',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::TWISTING_ISLES_OF_ILLUSIONS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#7950c4',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::GRASSLANDS_OF_GIANTS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#af9003',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::MENACING_MARCHES:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#1c8a9e',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::TROPICS_OF_TREPIDATION:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#869619',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::PERILOUS_PLANS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#4e56ce',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::TREACHEROUS_FOREST:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#b73583',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::SAVAGE_SWAMPS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#a56517',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::DESERT_OF_DESPAIR:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#99844a',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::HUMBLING_HILLS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#0ab5a1',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::GULF_OF_SERPENTS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#5b9621',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::CARNIVOROUS_CANYONS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#8e2964',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::INFERNAL_ISLANDS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#c95a00',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::JADE_JUNGLE:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#377a23',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::SHORES_OF_THE_SHADOWS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#9e775a',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::VALLEY_OF_VANISHINGS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#8339ba',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::PRIMAL_PASSAGE:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#5844c9',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::ARCTIC_ARCHIPELAGO:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#586b6d',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::BADLANDS_OF_THE_LEVIATHAN:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#8c6d4f',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::PEAKS_OF_PANDORA:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#a3277c',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::CRYPTIC_COAST:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#ba3f3f',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::WETLANDS_OF_WANDERING_TORMENT:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#196e96',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::BLOODSTONE_BEACHES:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#af9f08',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::TURBULENT_TUNDRA:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#5ba373',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::COVES_OF_CORRUPTION:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#c62e1d',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::MOUNTAINS_OF_MISERY:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#6c6491',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::ENCLAVE_OF_ENIGMAS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#598e47',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::SCREAMING_HIGHLANDS_OF_NIGHTMARES:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#FFFFFF',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::CLOUD_PIERCING_CLIFFS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#FFFFFF',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
            case self::OUTER_RIM_OF_THE_DEMONIC:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#FFFFFF',
                        [
                            'pan_x' => 0,
                            'pan_y' => 0,
                            'zoom_x' => 315,
                            'zoom_y' => 240
                        ]
                    )
                );
        }

        throw new UnknownBehaviorException($this->name, TerritoryBehavior::class);
    }

    public function realmColor()
    {
        return $this->getBehavior()->getRealmColor();
    }

    public function realmViewBox()
    {
        return $this->getBehavior()->getRealmViewBox();
    }
}
