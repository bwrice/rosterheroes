<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Models\StatType;

abstract class QualityBehavior extends MeasurableTypeBehavior
{
    public const GROUP_NAME = 'quality';

    public function __construct()
    {
        $this->group = self::GROUP_NAME;
    }
}
