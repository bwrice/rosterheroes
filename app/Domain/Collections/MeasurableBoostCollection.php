<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/18/19
 * Time: 11:30 AM
 */

namespace App\Domain\Collections;


use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableBoost;
use Illuminate\Database\Eloquent\Collection;

class MeasurableBoostCollection extends Collection
{
    public function boostLevelSum()
    {
        return (int) $this->sum(function (MeasurableBoost $measurableBoost) {
            return $measurableBoost->boost_level;
        });
    }

    public function filterByMeasurableType(string $measurableTypeName)
    {
        return $this->filter(function (MeasurableBoost $measurableBoost) use ($measurableTypeName) {
            return $measurableBoost->measurableType->name === $measurableTypeName;
        });
    }

    public function boostTotal()
    {
        return $this->sum(function (MeasurableBoost $measurableBoost) {
            return $measurableBoost->getBoostAmount();
        });
    }
}
