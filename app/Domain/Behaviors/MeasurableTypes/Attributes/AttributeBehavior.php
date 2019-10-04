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

    public function getBaseAmount(): int
    {
        return 20;
    }

    public function getMeasurableImportanceWeight(): float
    {
        return 1;
    }

    public function getBoostMultiplier(BoostsMeasurables $boostsMeasurables): int
    {
        return $boostsMeasurables->getAttributeBoostMultiplier();
    }
}
