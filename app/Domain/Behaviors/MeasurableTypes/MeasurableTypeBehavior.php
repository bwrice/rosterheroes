<?php


namespace App\Domain\Behaviors\MeasurableTypes;

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

    public function getFantasyPointsModifier(int $buffedAmount)
    {
        return $buffedAmount/100;
    }
}
