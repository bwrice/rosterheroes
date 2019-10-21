<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MeasurableTypes\Attributes\AttributeBehavior;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\QualityBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ResourceBehavior;
use App\Domain\Collections\MeasurableBoostCollection;
use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Models\MeasurableBoost;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Spell
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 *
 * @property MeasurableBoostCollection $measurableBoosts
 */
class Spell extends Model implements BoostsMeasurables
{
    const RELATION_MORPH_MAP_KEY = 'spells';

    protected $guarded = [];

    public static function getResourceRelations()
    {
        return [
            'measurableBoosts.measurableType',
            'measurableBoosts.booster'
        ];
    }

    public function measurableBoosts()
    {
        return $this->morphMany(MeasurableBoost::class, 'booster' );
    }

    public function manaCost(): int
    {
        $boosterCount = $this->measurableBoosts->count();
        if (! $boosterCount) {
            return 0;
        }
        $boostLevelSum = $this->measurableBoosts->boostLevelSum();
        return ceil(10 * ($boostLevelSum ** .85) * (1/sqrt($boosterCount)));
    }

    public function getMeasurableBoostMultiplier(MeasurableTypeBehavior $measurableTypeBehavior): int
    {
        switch($measurableTypeBehavior->getGroupName()) {
            case ResourceBehavior::GROUP_NAME:
                return 8;
            case QualityBehavior::GROUP_NAME:
                return 4;
            case AttributeBehavior::GROUP_NAME:
                return 2;
        }
        throw new \InvalidArgumentException("Unknown group name: " . $measurableTypeBehavior->getGroupName());
    }
}
