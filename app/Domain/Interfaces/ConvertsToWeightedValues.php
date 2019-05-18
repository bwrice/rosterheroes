<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/18/19
 * Time: 1:22 PM
 */

namespace App\Domain\Interfaces;


use App\Domain\Collections\WeightedValueCollection;

interface ConvertsToWeightedValues
{
    public function toWeightedValues(): WeightedValueCollection;
}