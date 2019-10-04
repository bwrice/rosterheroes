<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Interfaces\BoostsMeasurables;

abstract class QualityBehavior extends MeasurableTypeBehavior
{
    public const GROUP_NAME = 'quality';

    public function __construct()
    {
        $this->group = self::GROUP_NAME;
    }


    public function getBaseAmount(): int
    {
        return 100;
    }

    public function getMeasurableImportanceWeight(): float
    {
        return .5;
    }

    public function getBoostMultiplier(BoostsMeasurables $boostsMeasurables): int
    {
        return $boostsMeasurables->getQualityBoostMultiplier();
    }
}
