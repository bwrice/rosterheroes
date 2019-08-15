<?php


namespace App\Domain\Behaviors\MeasurableTypes;


use App\Domain\Behaviors\MeasurableTypes\MeasurableGroups\MeasurableGroup;

abstract class MeasurableTypeBehavior
{
    /**
     * @var MeasurableGroup
     */
    private $measurableGroup;

    public function __construct(MeasurableGroup $attributeGroup)
    {
        $this->measurableGroup = $attributeGroup;
    }


    abstract public function getBaseAmount(): int;

    public function getCostToRaiseCoefficientMultiplier(): float
    {
        //TODO
        return 1;
    }
}
