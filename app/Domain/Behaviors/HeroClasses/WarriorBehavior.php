<?php


namespace App\Domain\Behaviors\HeroClasses;


use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\MeasurableType;

class WarriorBehavior extends HeroClassBehavior
{
    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_SHIELD,
            ItemBlueprint::STARTER_SWORD
        ];
    }

    protected function getMeasurableStartingBonusAmount($measurableTypeName): int
    {
        switch ($measurableTypeName) {
            case MeasurableType::STRENGTH:
            case MeasurableType::VALOR:
                return 40;
            case MeasurableType::AGILITY:
                return 20;
            case MeasurableType::HEALTH:
                return 250;
            case MeasurableType::STAMINA:
                return 100;
        }

        return 0;
    }

    protected function getCostToRaiseCoefficient($measurableTypeName): int
    {
        switch ($measurableTypeName) {
            case MeasurableType::STRENGTH:
            case MeasurableType::VALOR:
            case MeasurableType::HEALTH:
                return 40;
            case MeasurableType::AGILITY:
            case MeasurableType::STAMINA:
                return 60;
        }
        return 75;
    }

    protected function getCostToRaiseExponent($measurableTypeName): float
    {
        switch ($measurableTypeName) {
            case MeasurableType::STRENGTH:
            case MeasurableType::VALOR:
            case MeasurableType::HEALTH:
                return 1.9;
            case MeasurableType::AGILITY:
            case MeasurableType::STAMINA:
                return 2.05;
        }
        return 2.2;
    }
}
