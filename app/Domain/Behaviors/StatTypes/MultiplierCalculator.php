<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/24/19
 * Time: 7:59 PM
 */

namespace App\Domain\Behaviors\StatTypes;


class MultiplierCalculator implements PointsCalculator
{

    private $pointsPer;

    public function __construct(float $pointsPer)
    {
        $this->pointsPer = $pointsPer;
    }

    /**
     * @param $amount
     * @return int|float
     */
    public function total($amount): float
    {
        return round($this->pointsPer * $amount, 2);
    }
}