<?php


namespace App\Domain\Behaviors\HeroClasses\MeasurableOperators;


use App\Domain\Interfaces\MeasurableCalculator;
use App\Domain\Interfaces\MeasurableOperator;
use App\Domain\Models\Measurable;

class HeroMeasurableCalculator implements MeasurableCalculator
{
    /**
     * @param Measurable $measurable
     * @param MeasurableOperator $operator
     * @return int
     */
    public function getCostToRaiseMeasurable(Measurable $measurable, MeasurableOperator $operator): int
    {
        $K = $operator->getCostToRaiseCoefficient($measurable);
        $n = $operator->getCostToRaiseExponent($measurable);
        $x = $measurable->amount_raised + 1;

        return (int) round(($K * $x) + ($x**$n));
    }

    /**
     * @param Measurable $measurable
     * @param MeasurableOperator $operator
     * @return int
     */
    public function getCurrentMeasurableAmount(Measurable $measurable, MeasurableOperator $operator): int
    {
        $currentAmount = $operator->getBaseAmount($measurable) + $measurable->amount_raised;
        $currentAmount += $operator->getCurrentAmountBonus($measurable);
        return $currentAmount;
    }
}
