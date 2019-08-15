<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class QualityBehavior extends MeasurableTypeBehavior
{
    public function getBaseAmount(): int
    {
        return 100;
    }
}
