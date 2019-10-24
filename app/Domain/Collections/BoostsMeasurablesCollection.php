<?php


namespace App\Domain\Collections;


use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Models\Enchantment;
use App\Domain\Models\MeasurableBoost;
use Illuminate\Database\Eloquent\Collection;

abstract class BoostsMeasurablesCollection extends Collection
{
    public function measurableBoosts(): MeasurableBoostCollection
    {
        $boostsCollection = new MeasurableBoostCollection();
        $this->each(function (BoostsMeasurables $boostMeasurables) use ($boostsCollection) {
            $boostMeasurables->getMeasurableBoosts()->each(function (MeasurableBoost $measurableBoost) use ($boostsCollection) {
                $boostsCollection->push($measurableBoost);
            });
        });
        return $boostsCollection;
    }

    public function getBoostAmount(string $measurableTypeName): int
    {
        return $this->measurableBoosts()
            ->filterByMeasurableType($measurableTypeName)
            ->boostTotal();
    }

    /**
     * @return int
     */
    public function boostLevelSum()
    {
        return (int) $this->loadMissing('measurableBoosts')->sum(function (BoostsMeasurables $boostsMeasurables) {
            return $boostsMeasurables->getMeasurableBoosts()->boostLevelSum();
        });
    }
}
