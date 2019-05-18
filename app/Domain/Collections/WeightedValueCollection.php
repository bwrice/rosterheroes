<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/18/19
 * Time: 1:22 PM
 */

namespace App\Domain\Collections;


use App\Domain\Math\WeightedValue;
use Illuminate\Support\Collection;
use MathPHP\Statistics\Average;

class WeightedValueCollection extends Collection
{
    /**
     * @return float
     * @throws \MathPHP\Exception\BadDataException
     */
    public function getWeightedMean()
    {
        return Average::weightedMean($this->getNumbers(), $this->getWeights());
    }

    /**
     * @return float
     * @throws \MathPHP\Exception\BadDataException
     */
    public function getMean()
    {
        return Average::mean($this->getNumbers());
    }

    /**
     * @return array
     */
    public function getWeights(): array
    {
        return $this->map(function (WeightedValue $weightedValue) {
            return $weightedValue->getWeight();
        })->values()->toArray();
    }

    /**
     * @return array
     */
    public function getNumbers(): array
    {
        return $this->map(function (WeightedValue $weightedValue) {
            return $weightedValue->getValue();
        })->values()->toArray();
    }
}