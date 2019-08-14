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
        switch($measurableTypeName) {
            case MeasurableType::STRENGTH:
                return 10;
            case MeasurableType::AGILITY:
                return 30;
            case MeasurableType::FOCUS:
                return 40;
        }

        return 0;
    }
}
