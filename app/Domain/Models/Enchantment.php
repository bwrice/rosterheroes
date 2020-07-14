<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MeasurableTypes\Attributes\AttributeBehavior;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\QualityBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ResourceBehavior;
use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Collections\MeasurableBoostCollection;
use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Models\MeasurableBoost;
use App\Http\Resources\AttackResource;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Enchantment
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 * @property int $restriction_level
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

    public function getMeasurableBoostMultiplier(MeasurableTypeBehavior $measurableTypeBehavior): float
    {
        switch($measurableTypeBehavior->getGroupName()) {
            case ResourceBehavior::GROUP_NAME:
                return 4;
            case QualityBehavior::GROUP_NAME:
                return 2;
            case AttributeBehavior::GROUP_NAME:
                return 1;
        }
        throw new \InvalidArgumentException("Unknown group name: " . $measurableTypeBehavior->getGroupName());
    }

    public function getMeasurableBoosts(): MeasurableBoostCollection
    {
        return $this->measurableBoosts;
    }
}
