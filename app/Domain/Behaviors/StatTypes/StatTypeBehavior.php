<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/17/19
 * Time: 9:45 PM
 */

namespace App\Domain\Behaviors\StatTypes;


class StatTypeBehavior
{

    /**
     * @var PointsCalculator
     */
    private $pointsCalculator;

    public function __construct(PointsCalculator $pointsCalculator)
    {
        $this->pointsCalculator = $pointsCalculator;
    }

    /**
     * @param $statAmount
     * @return float
     */
    public function getTotalPoints($statAmount): float
    {
        return $this->pointsCalculator->total($statAmount);
    }

    /**
     * @return float
     */
    public function getPointsPer()
    {
        return $this->pointsCalculator->pointsPer();
    }
}