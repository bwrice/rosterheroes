<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class ResourceBehavior extends MeasurableTypeBehavior
{
    public function getMeasurableImportanceWeight(): float
    {
        return .25;
    }

    public function getMeasurableGroup(): string
    {
        return 'resource';
    }
}
