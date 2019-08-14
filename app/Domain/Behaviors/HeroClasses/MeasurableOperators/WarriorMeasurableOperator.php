<?php


namespace App\Domain\Behaviors\HeroClasses\MeasurableOperators;


use App\Domain\Models\MeasurableType;

class WarriorMeasurableOperator extends HeroMeasurableOperator
{
    protected function primaryMeasurableTypes(): array
    {
        return [
            MeasurableType::STRENGTH,
            MeasurableType::VALOR,
            MeasurableType::HEALTH
        ];
    }

    protected function secondaryMeasurableTypes(): array
    {
        return [
            MeasurableType::AGILITY,
            MeasurableType::STAMINA,
        ];
    }
}
