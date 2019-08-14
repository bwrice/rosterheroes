<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Models\ItemBlueprint;

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
}
