<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class QualityBehavior extends MeasurableTypeBehavior
{
    public function getBaseAmount(): int
    {
        return 100;
    }

    public function getMeasurableImportanceWeight(): float
    {
        return .5;
    }

    public function getMeasurableGroup(): string
    {
        return 'quality';
    }
}
