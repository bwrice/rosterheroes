<?php


namespace App\Domain\Collections;


use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Models\Enchantment;
use Illuminate\Database\Eloquent\Collection;

abstract class BoostsMeasurablesCollection extends Collection
{
    public function measurableBoosts(): MeasurableBoostCollection
    {
        $boosts = new MeasurableBoostCollection();
        $this->each(function (BoostsMeasurables $boostMeasurables) use (&$boosts) {
            $boosts = $boosts->union($boostMeasurables->getMeasurableBoosts());
        });
        return $boosts;
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
