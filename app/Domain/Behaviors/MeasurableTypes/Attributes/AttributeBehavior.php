<?php


namespace App\Domain\Behaviors\MeasurableTypes\Attributes;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class AttributeBehavior extends MeasurableTypeBehavior
{

    public const GROUP_NAME = 'attribute';

    public function getBaseAmount(): int
    {
        return 20;
    }

    public function getMeasurableImportanceWeight(): float
    {
        return 1;
    }

    public function getMeasurableGroup(): string
    {
        return self::GROUP_NAME;
    }

    public function getEnchantmentBoostMultiplier(): int
    {
        return 1;
    }
}
