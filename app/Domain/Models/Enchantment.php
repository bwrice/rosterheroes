<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MeasurableTypes\Qualities\QualityBehavior;
use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Collections\MeasurableBoostCollection;
use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Models\MeasurableBoost;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Enchantment
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 *
 * @property MeasurableBoostCollection $measurableBoosts
 */
class Enchantment extends Model implements BoostsMeasurables
{
    const RELATION_MORPH_MAP_KEY = 'enchantments';

    protected $guarded = [];

    public function measurableBoosts()
    {
        return $this->morphMany(MeasurableBoost::class, 'booster' );
    }

    public function newCollection(array $models = [])
    {
        return new EnchantmentCollection($models);
    }

    public function boostLevelSum()
    {
        return $this->measurableBoosts->boostLevelSum();
    }

//    public function getBoostAmount(int $boostLevel, MeasurableType $measurableType): int
//    {
//        return $boostLevel * $measurableType->getBehavior()->getEnchantmentBoostMultiplier();
//    }

    public function getAttributeBoostMultiplier(): int
    {
        return 1;
    }

    public function getQualityBoostMultiplier(): int
    {
        return 2;
    }

    public function getResourceBoostMultiplier(): int
    {
        return 4;
    }
}
