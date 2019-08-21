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

    /**
     * @param Measurable $measurable
     * @param MeasurableOperator $operator
     * @return int
     */
    public function spentOnRaising(Measurable $measurable, MeasurableOperator $operator): int
    {
        $amountRaised = $measurable->amount_raised;
        if ($amountRaised < 1) {
            return 0;
        }
        return $this->sumCostToRaise($measurable, $operator, 1, $amountRaised);
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


    protected function calculateSingleCostToRaise(int $newAmountRaised, float $base, float $exponent): int
    {
        /*
         * Subtract 1 from new amount raised so that a measurable not raised
         * will only cost the base amount for the initial raise
         */
        return (int) round($base + (($base/4) * ($newAmountRaised - 1) ** $exponent));
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
