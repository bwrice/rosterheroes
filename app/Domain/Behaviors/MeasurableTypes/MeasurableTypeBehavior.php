<?php


namespace App\Domain\Behaviors\MeasurableTypes;


abstract class MeasurableTypeBehavior
{
    abstract public function getBaseAmount(): int;

    public function getCostToRaiseCoefficientMultiplier(): float
    {
        //TODO
        return 1;
    }
}
