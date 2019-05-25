<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/24/19
 * Time: 7:57 PM
 */

namespace App\Domain\Behaviors\StatTypes;


interface PointsCalculator
{
    /**
     * @param $amount
     * @return int|float
     */
    public function total($amount): float;
}