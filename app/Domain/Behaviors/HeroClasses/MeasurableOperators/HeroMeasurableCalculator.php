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
        $amountRaised = $measurable->amount_raised;
        $startRange = $amountRaised + 1;
        $endRange = $amountRaised + $amount;
        return $this->sumCostToRaise($measurable, $operator, $startRange, $endRange);
    }

    public function spentOnRaising(Measurable $measurable, MeasurableOperator $operator): int
    {
        //
    }

    protected function sumCostToRaise(Measurable $measurable, MeasurableOperator $operator, int $raisedAmountStart, int $raisedAmountEnd)
    {
        $base = $operator->getCostToRaiseBaseAmount($measurable);
        $exponent = $operator->getCostToRaiseExponent($measurable);
        $totalCost = 0;
        foreach (range($raisedAmountStart, $raisedAmountEnd) as $amountRaised) {
            $totalCost += $this->calculateSingleCostToRaise($amountRaised, $base, $exponent);
        }
        return $totalCost;
    }


    protected function calculateSingleCostToRaise(int $amountRaised, float $base, float $exponent): int
    {
        return (int) round($base + (($base/4) * ($amountRaised - 1) ** $exponent));
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
