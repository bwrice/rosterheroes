<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;


use App\Domain\Behaviors\MeasurableTypes\MeasurableGroups\MeasurableGroup;
use App\Domain\Behaviors\MeasurableTypes\MeasurableGroups\ResourceMeasurableGroup;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class ResourceBehavior extends MeasurableTypeBehavior
{
    public function __construct(ResourceMeasurableGroup $resourceGroup)
    {
        parent::__construct($resourceGroup);
    }
}
