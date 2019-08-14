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
        switch($measurableTypeName) {
            case MeasurableType::FOCUS:
                return 10;
            case MeasurableType::APTITUDE:
                return 30;
            case MeasurableType::INTELLIGENCE:
                return 40;
        }

        return 0;
    }
}
