<?php


namespace App\Domain\Behaviors\HeroClasses\MeasurableOperators;


use App\Domain\Models\MeasurableType;

class SorcererMeasurableOperator extends HeroMeasurableOperator
{
    protected function primaryMeasurableTypes(): array
    {
        return [
            MeasurableType::APTITUDE,
            MeasurableType::INTELLIGENCE,
            MeasurableType::MANA
        ];
    }

    protected function secondaryMeasurableTypes(): array
    {
        return [
            MeasurableType::FOCUS,
            MeasurableType::HEALTH,
        ];
    }
}
