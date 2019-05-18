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
     * @var float
     */
    private $pointsPer;

    public function __construct(float $pointsPer)
    {
        $this->pointsPer = $pointsPer;
    }

    /**
     * @return float
     */
    public function getPointsPer(): float
    {
        return $this->pointsPer;
    }
}