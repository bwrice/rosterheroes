<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Interfaces\BoostsMeasurables;

abstract class ResourceBehavior extends MeasurableTypeBehavior
{
    public const GROUP_NAME = 'resource';

    public function getMeasurableImportanceWeight(): float
    {
        return .125;
    }

    public function getMeasurableGroup(): string
    {
        return self::GROUP_NAME;
    }

    public function getBoostMultiplier(BoostsMeasurables $boostsMeasurables): int
    {
        return $boostsMeasurables->getResourceBoostMultiplier();
    }
}
