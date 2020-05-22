<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class QualityBehavior extends MeasurableTypeBehavior
{
    public const GROUP_NAME = 'quality';

    public function __construct()
    {
        $this->group = self::GROUP_NAME;
    }
}
