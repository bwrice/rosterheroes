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

    /**
     * @return int
     */
    public function getBaseAmount(): int
    {
        return $this->baseAmount;
    }

    public function getCostToRaiseCoefficientMultiplier(): float
    {
        //TODO
        return 1;
    }
}
