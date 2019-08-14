<?php


namespace App\Domain\Behaviors\HeroClasses\MeasurableOperators;


use App\Domain\Interfaces\MeasurableOperator;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;

abstract class HeroMeasurableOperator implements MeasurableOperator
{

    public function getCostToRaiseCoefficient(Measurable $measurable): float
    {
        if ($this->isPrimaryType($measurable->measurableType)) {
            $coefficient = 40;
        } elseif ($this->isSecondaryType($measurable->measurableType)) {
            $coefficient = 60;
        } else {
            $coefficient = 75;
        }

        return $measurable->getCostToRaiseCoefficientMultiplier() * $coefficient;
    }

    public function getCostToRaiseExponent(Measurable $measurable): float
    {
        if ($this->isPrimaryType($measurable->measurableType)) {
            return 1.9;
        } elseif ($this->isSecondaryType($measurable->measurableType)) {
            return 2.1;
        }

        return 2.25;
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

    abstract public function isPrimaryType(MeasurableType $measurableType): bool;

    abstract public function isSecondaryType(MeasurableType $measurableType): bool;

}
