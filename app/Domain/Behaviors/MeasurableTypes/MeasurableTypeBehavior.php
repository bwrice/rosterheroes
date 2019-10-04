<?php


namespace App\Domain\Behaviors\MeasurableTypes;

use App\Domain\Interfaces\BoostsMeasurables;

abstract class MeasurableTypeBehavior
{

    protected $name = '';
    protected $group = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    abstract public function getBaseAmount(): int;

    abstract public function getMeasurableImportanceWeight(): float;

    abstract public function getBoostMultiplier(BoostsMeasurables $boostsMeasurables): int;
}
