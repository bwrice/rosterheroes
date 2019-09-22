<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;

use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

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

    public function getEnchantmentBoostMultiplier(): int
    {
        return 4;
    }
}
