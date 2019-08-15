<?php


namespace App\Domain\Behaviors\MeasurableTypes;


abstract class MeasurableTypeBehavior
{
    abstract public function getBaseAmount(): int;

    abstract public function getMeasurableImportanceWeight(): float;
}
