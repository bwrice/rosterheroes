<?php

namespace App\Domain\Models;

use App\Domain\Collections\MeasurableCollection;
use App\Domain\Interfaces\HasMeasurables;

/**
 * Class Measurable
 * @package App
 *
 * @property int $id
 * @property int $measurable_type_id
 * @property int $amount_raised
 *
 * @property HasMeasurables $hasMeasurables
 * @property MeasurableType $measurableType
 */
class Measurable extends EventSourcedModel
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new MeasurableCollection($models);
    }

    public function measurableType()
    {
        return $this->belongsTo(MeasurableType::class);
    }

    public function hasMeasurables()
    {
        return $this->morphTo();
    }

    public function getCostToRaise(int $amount = 1): int
    {
        return $this->hasMeasurables->costToRaiseMeasurable($this, $amount);
    }

    public function spentOnRaising(): int
    {
        return $this->hasMeasurables->spentOnRaisingMeasurable($this);
    }

    public function getCurrentAmount(): int
    {
        $hasMeasurables = $this->hasMeasurables;
        $startingAmount = $hasMeasurables->getMeasurableStartingAmount($this->getMeasurableTypeBehavior());
        return $startingAmount + $this->amount_raised;
    }

    public function getBaseAmount(): int
    {
        return $this->measurableType->getBehavior()->getBaseAmount();
    }

    public function getMeasurableImportanceWeight()
    {
        return $this->measurableType->getBehavior()->getMeasurableImportanceWeight();
    }

    public function getMeasurableGroup(): string
    {
        return $this->measurableType->getBehavior()->getGroupName();
    }

    public function getMeasurableTypeBehavior()
    {
        return $this->measurableType->getBehavior();
    }
}
