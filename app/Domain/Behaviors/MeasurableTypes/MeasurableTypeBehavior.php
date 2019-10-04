<?php


namespace App\Domain\Behaviors\MeasurableTypes;

use App\Domain\Interfaces\BoostsMeasurables;

abstract class MeasurableTypeBehavior
{

    protected $name = '';
    protected $group = '';

    abstract public function getBaseAmount(): int;

    abstract public function getMeasurableImportanceWeight(): float;

    abstract public function getMeasurableGroup(): string;

    abstract public function getBoostMultiplier(BoostsMeasurables $boostsMeasurables): int;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
