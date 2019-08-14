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
        switch($measurableTypeName) {
            case MeasurableType::STRENGTH:
                return 30;
            case MeasurableType::VALOR:
                return 40;
            case MeasurableType::STAMINA:
                return 10;
        }

        return 0;
    }
}
