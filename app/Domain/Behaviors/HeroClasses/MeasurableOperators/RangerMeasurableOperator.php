<?php


namespace App\Domain\Behaviors\HeroClasses\MeasurableOperators;


use App\Domain\Models\MeasurableType;

class RangerMeasurableOperator extends HeroMeasurableOperator
{
    protected function primaryMeasurableTypes(): array
    {
        return [
            MeasurableType::AGILITY,
            MeasurableType::FOCUS,
            MeasurableType::STAMINA
        ];
    }

    protected function secondaryMeasurableTypes(): array
    {
        return [
            MeasurableType::STRENGTH,
            MeasurableType::MANA,
        ];
    }
}
