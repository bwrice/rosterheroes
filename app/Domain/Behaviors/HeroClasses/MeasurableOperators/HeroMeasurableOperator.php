<?php


namespace App\Domain\Behaviors\HeroClasses\MeasurableOperators;


use App\Domain\Interfaces\MeasurableOperator;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;

abstract class HeroMeasurableOperator implements MeasurableOperator
{
    abstract protected function primaryMeasurableTypes(): array;

    abstract protected function secondaryMeasurableTypes(): array;

    public function getCostToRaiseBaseAmount(Measurable $measurable): float
    {
        if ($this->isPrimaryType($measurable->measurableType)) {
            $baseAmount = 40;
        } elseif ($this->isSecondaryType($measurable->measurableType)) {
            $baseAmount = 60;
        } else {
            $baseAmount = 75;
        }

        return $measurable->getMeasurableImportanceWeight() * $baseAmount;
    }

    public function getCostToRaiseExponent(Measurable $measurable): float
    {
        if ($this->isPrimaryType($measurable->measurableType)) {
            return 1.85;
        } elseif ($this->isSecondaryType($measurable->measurableType)) {
            return 2;
        }

        return 2.15;
    }

    public function getBaseAmount(Measurable $measurable): int
    {
        return $measurable->getBaseAmount() + $this->getCurrentAmountBonus($measurable);
    }

    public function getCurrentAmountBonus(Measurable $measurable): int
    {
        if ($this->isPrimaryType($measurable->measurableType)) {
            return 40;
        } elseif ($this->isSecondaryType($measurable->measurableType)) {
            return 20;
        }

        return 0;
    }

    protected function isPrimaryType(MeasurableType $measurableType)
    {
        return in_array($measurableType->name, $this->primaryMeasurableTypes());
    }

    protected function isSecondaryType(MeasurableType $measurableType)
    {
        return in_array($measurableType->name, $this->secondaryMeasurableTypes());
    }

}
