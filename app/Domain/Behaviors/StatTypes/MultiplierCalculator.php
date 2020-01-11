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

    /**
     * @param $pointsPer
     * @param $amount
     * @return int|float
     */
    public function total($pointsPer, $amount): float
    {
        return round($pointsPer * $amount, 2);
    }
}
