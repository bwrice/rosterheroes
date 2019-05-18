<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/18/19
 * Time: 12:55 PM
 */

namespace App\Domain\Math;


class WeightedValue
{
    /**
     * @var float
     */
    private $weight;
    /**
     * @var float
     */
    private $value;

    public function __construct(float $weight, float $value)
    {
        $this->weight = $weight;
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}