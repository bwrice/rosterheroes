<?php


namespace App\Domain\Behaviors\MeasurableTypes\Attributes;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Interfaces\BoostsMeasurables;

abstract class AttributeBehavior extends MeasurableTypeBehavior
{
    public const GROUP_NAME = 'attribute';

    public function __construct()
    {
        $this->group = self::GROUP_NAME;
    }
}
