<?php

namespace App\Domain\Models;

use App\Domain\Collections\MeasurableBoostCollection;
use App\Domain\Interfaces\BoostsMeasurables;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MeasurableBoost
 * @package App\Domain\Models
 *
 * @property int $boost_level
 *
 * @property MeasurableType $measurableType
 */
class MeasurableBoost extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new MeasurableBoostCollection($models);
    }

    public function measurableType()
    {
        return $this->belongsTo(MeasurableType::class);
    }

    public function getBoostAmount(BoostsMeasurables $boostsMeasurables = null): int
    {
        if (! $boostsMeasurables) {
            return $this->boost_level;
        }
        return $this->boost_level * $this->measurableType->getBehavior()->getBoostMultiplier($boostsMeasurables);
    }

    public function getDescription(BoostsMeasurables $boostsMeasurables = null): string
    {
        $boostAmount = $this->getBoostAmount($boostsMeasurables);
        return "+" . $boostAmount . " " . ucwords($this->measurableType->name);
    }
}
