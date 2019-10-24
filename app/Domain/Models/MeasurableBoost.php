<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Collections\MeasurableBoostCollection;
use App\Domain\Interfaces\BoostsMeasurables;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MeasurableBoost
 * @package App\Domain\Models
 *
 * @property int $boost_level
 * @property int $measurable_type_id
 *
 * @property MeasurableType $measurableType
 * @property BoostsMeasurables $booster
 */
class MeasurableBoost extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new MeasurableBoostCollection($models);
    }

    public function booster()
    {
        return $this->morphTo();
    }

    public function measurableType()
    {
        return $this->belongsTo(MeasurableType::class);
    }

    public function getBoostAmount(): int
    {
        $boostMultiplier = $this->booster->getMeasurableBoostMultiplier($this->getMeasurableTypeBehavior());
        return (int) floor($this->boost_level * $boostMultiplier);
    }

    public function getDescription(): string
    {
        $boostAmount = $this->getBoostAmount();
        return "+" . $boostAmount . " " . ucwords($this->measurableType->name);
    }

    /**
     * @return MeasurableTypeBehavior
     */
    protected function getMeasurableTypeBehavior(): MeasurableTypeBehavior
    {
        return $this->measurableType->getBehavior();
    }
}
