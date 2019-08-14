<?php


namespace App\Domain\Behaviors\HeroClasses;


use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\MeasurableType;

class RangerBehavior extends HeroClassBehavior
{
    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_BOW
        ];
    }

    protected function getMeasurableStartingBonusAmount($measurableTypeName): int
    {
        switch ($measurableTypeName) {
            case MeasurableType::STRENGTH:
                return 20;
            case MeasurableType::AGILITY:
            case MeasurableType::FOCUS:
                return 40;
            case MeasurableType::HEALTH:
                return 100;
            case MeasurableType::STAMINA:
                return 250;
        }

        return 0;
    }

    protected function getCostToRaiseCoefficient($measurableTypeName): int
    {
        switch ($measurableTypeName) {
            case MeasurableType::AGILITY:
            case MeasurableType::FOCUS:
            case MeasurableType::STAMINA:
                return 40;
            case MeasurableType::STRENGTH:
            case MeasurableType::HEALTH:
                return 60;
        }
        return 75;
    }

    protected function getCostToRaiseExponent($measurableTypeName): float
    {
        switch ($measurableTypeName) {
            case MeasurableType::AGILITY:
            case MeasurableType::FOCUS:
            case MeasurableType::STAMINA:
                return 40;
            case MeasurableType::STRENGTH:
            case MeasurableType::HEALTH:
                return 60;
        }
        return 75;
    }
}
