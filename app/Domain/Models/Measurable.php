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
 * @property Hero $hero
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

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }

    public function getCostToRaise(int $amountToRaise = 1): int
    {
        return $this->hero->costToRaiseMeasurable($this->getMeasurableTypeBehavior(), $this->amount_raised, $amountToRaise);
    }

    public function spentOnRaising(): int
    {
        return $this->hero->spentOnRaisingMeasurable($this->getMeasurableTypeBehavior(), $this->amount_raised);
    }

    public function getPreBuffedAmount(): int
    {
        $startingAmount = $this->getMeasurableStartingAmount();
        return $startingAmount + $this->amount_raised;
    }

    public function getBuffedAmount(): int
    {
        $preBuffedAmount = $this->getPreBuffedAmount();
        $buffsAmount = $this->hero->getBuffsSumAmountForMeasurable($this->getMeasurableTypeBehavior());
        return $preBuffedAmount + $buffsAmount;
    }

    public function getCurrentAmount(): int
    {
        $buffedAmount = $this->getBuffedAmount();
        $amountUsed = $this->hero->getAmountUsedForMeasurable($this->getMeasurableTypeBehavior());
        return $buffedAmount - $amountUsed;
    }

    public function getMeasurableTypeBehavior()
    {
        return $this->measurableType->getBehavior();
    }

    protected function getMeasurableStartingAmount(): int
    {
        return $this->hero->getMeasurableStartingAmount($this->getMeasurableTypeBehavior());
    }
}
