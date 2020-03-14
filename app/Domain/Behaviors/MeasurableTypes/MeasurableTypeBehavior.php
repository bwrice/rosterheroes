<?php


namespace App\Domain\Behaviors\MeasurableTypes;

abstract class MeasurableTypeBehavior
{

    protected $name = '';
    protected $group = '';
    protected $statTypeNames = [];

    public function getTypeName(): string
    {
        return $this->name;
    }

    public function getGroupName(): string
    {
        return $this->group;
    }

    /**
     * @return array
     */
    public function getStatTypeNames(): array
    {
        return $this->statTypeNames;
    }
}
