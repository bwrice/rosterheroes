<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Realm\RealmBehavior;
use App\Domain\Behaviors\Realm\Territories\TerritoryBehavior;
use App\Domain\Behaviors\Realm\TerritoryBehaviorFactory;
use App\Domain\Models\Continent;
use App\Domain\Traits\HasNameSlug;
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
    use HasNameSlug;

    const GARDENS_OF_REDEMPTION = 'Gardens of Redemption';
    const WOODS_OF_THE_WILD = 'Woods of the Wild';
    const TWISTING_ISLES_OF_ILLUSIONS = 'Twisting Isles of Illusions';
    const GRASSLANDS_OF_GIANTS = 'Grasslands of Giants';
    const MENACING_MARCHES = 'Menacing Marshes';
    const TROPICS_OF_TREPIDATION = 'Tropics of Trepidation';
    const PERILOUS_PLAINS = 'Perilous Plains';
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

    public function getBehavior(): TerritoryBehavior
    {
        /** @var TerritoryBehaviorFactory $behaviorFactory */
       $behaviorFactory = app(TerritoryBehaviorFactory::class);
       return $behaviorFactory->getBehavior($this->name);
    }

    public function getRealmColor()
    {
        return $this->getBehavior()->getRealmColor();
    }

    public function getViewBox()
    {
        return $this->getBehavior()->getViewBox();
    }
}
