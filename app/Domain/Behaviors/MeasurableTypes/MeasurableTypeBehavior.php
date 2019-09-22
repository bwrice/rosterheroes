<?php


namespace App\Domain\Behaviors\MeasurableTypes;


abstract class MeasurableTypeBehavior
{
    abstract public function getBaseAmount(): int;

    abstract public function getMeasurableImportanceWeight(): float;

    abstract public function getMeasurableGroup(): string;

    abstract public function getEnchantmentBoostMultiplier(): int;
}
