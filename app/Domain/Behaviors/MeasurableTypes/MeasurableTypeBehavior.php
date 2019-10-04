<?php


namespace App\Domain\Behaviors\MeasurableTypes;

use App\Domain\Interfaces\BoostsMeasurables;

abstract class MeasurableTypeBehavior
{

    protected $name = '';
    protected $group = '';

    public function getTypeName(): string
    {
        return $this->name;
    }

    public function getGroupName(): string
    {
        return $this->group;
    }

    abstract public function getBoostMultiplier(BoostsMeasurables $boostsMeasurables): int;
}
