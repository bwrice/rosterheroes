<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\MeasurableType;

class SorcererBehavior extends HeroClassBehavior
{

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_STAFF
        ];
    }

    protected function getMeasurableStartingBonusAmount($measurableTypeName): int
    {
        switch ($measurableTypeName) {
            case MeasurableType::FOCUS:
                return 20;
            case MeasurableType::APTITUDE:
            case MeasurableType::INTELLIGENCE:
                return 40;
            case MeasurableType::HEALTH:
                return 100;
            case MeasurableType::MANA:
                return 250;
        }

        return 0;
    }

    protected function getCostToRaiseCoefficient($measurableTypeName): int
    {
        switch ($measurableTypeName) {
            case MeasurableType::APTITUDE:
            case MeasurableType::INTELLIGENCE:
            case MeasurableType::MANA:
                return 40;
            case MeasurableType::FOCUS:
            case MeasurableType::HEALTH:
                return 60;
        }
        return 75;
    }

    protected function getCostToRaiseExponent($measurableTypeName): float
    {
        switch ($measurableTypeName) {
            case MeasurableType::APTITUDE:
            case MeasurableType::INTELLIGENCE:
            case MeasurableType::MANA:
                return 40;
            case MeasurableType::FOCUS:
            case MeasurableType::HEALTH:
                return 60;
        }
        return 75;
    }
}
