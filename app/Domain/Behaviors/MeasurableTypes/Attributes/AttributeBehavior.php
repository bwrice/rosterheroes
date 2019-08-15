<?php


namespace App\Domain\Behaviors\MeasurableTypes\Attributes;


use App\Domain\Behaviors\MeasurableTypes\MeasurableGroups\AttributeMeasurableGroup;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class AttributeBehavior extends MeasurableTypeBehavior
{
    public function __construct(AttributeMeasurableGroup $attributeGroup)
    {
        parent::__construct($attributeGroup);
    }

    public function getBaseAmount(): int
    {
        return 20;
    }
}
