<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/24/19
 * Time: 8:07 PM
 */

namespace App\Domain\Behaviors\StatTypes;


class InningsPitchedCalculator implements PointsCalculator
{
    /**
     * @var MultiplierCalculator
     */
    private $multiplierCalculator;

    public function __construct(MultiplierCalculator $multiplierCalculator)
    {
        $this->multiplierCalculator = $multiplierCalculator;
    }

    /**
     * @param $pointsPer
     * @param $amount
     * @return int|float
     */
    public function total($pointsPer, $amount): float
    {
        return $this->multiplierCalculator->total($pointsPer, $this->getTrueInnings($amount));
    }

    /**
     * @param $amount
     * @return float|int
     */
    protected function getTrueInnings($amount)
    {
        $fullInnings = floor($amount);
        $fraction = $amount - $fullInnings;

        // convert .1 to .333, .2 to .667
        $partialInnings = $fraction * (10/3);
        return $fullInnings + $partialInnings;
    }
}
