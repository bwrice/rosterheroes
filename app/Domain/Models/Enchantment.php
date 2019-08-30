<?php

namespace App\Domain\Models;

use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Collections\MeasurableBoostCollection;
use App\Domain\Models\MeasurableBoost;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Enchantment
 * @package App\Domain\Models
 *
 * @property int $id
 *
 * @property MeasurableBoostCollection $measurableBoosts
 */
class Enchantment extends Model
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
}
