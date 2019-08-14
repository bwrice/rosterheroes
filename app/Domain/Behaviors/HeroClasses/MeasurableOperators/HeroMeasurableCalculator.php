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
        $base = $operator->getCostToRaiseBaseAmount($measurable);
        $exponent = $operator->getCostToRaiseExponent($measurable);
        $amountRaised = $measurable->amount_raised;

        return (int) round($base + (($base/4) * $amountRaised ** $exponent));
    }

    /**
     * @param Measurable $measurable
     * @param MeasurableOperator $operator
     * @return int
     */
    public function getCurrentMeasurableAmount(Measurable $measurable, MeasurableOperator $operator): int
    {
        $currentAmount = $operator->getBaseAmount($measurable) + $measurable->amount_raised;
        return $currentAmount;
    }
}
