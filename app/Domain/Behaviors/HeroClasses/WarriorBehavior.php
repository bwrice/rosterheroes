<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Models\ItemBlueprint;

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
}
