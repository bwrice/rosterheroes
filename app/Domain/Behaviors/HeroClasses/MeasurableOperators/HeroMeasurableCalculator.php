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
     * @param int $amount
     * @return int
     */
    public function getCostToRaise(Measurable $measurable, MeasurableOperator $operator, int $amount = 1): int
    {
        $base = $operator->getCostToRaiseBaseAmount($measurable);
        $exponent = $operator->getCostToRaiseExponent($measurable);
        $amountRaised = $measurable->amount_raised;

        $amount = $amount >= 1 ? $amount : 1;
        $totalCost = 0;
        foreach(range(1, $amount) as $count) {
            $totalCost += $this->calculateCostToRaise($amountRaised, $base, $exponent);
            $amountRaised++;
        }
        return $totalCost;
    }

    protected function calculateCostToRaise(int $amountRaised, float $base, float $exponent): int
    {
        return (int) round($base + (($base/4) * $amountRaised ** $exponent));
    }

    /**
     * @param Measurable $measurable
     * @param MeasurableOperator $operator
     * @return int
     */
    public function getCurrentAmount(Measurable $measurable, MeasurableOperator $operator): int
    {
        $currentAmount = $operator->getBaseAmount($measurable) + $measurable->amount_raised;
        return $currentAmount;
    }
}
