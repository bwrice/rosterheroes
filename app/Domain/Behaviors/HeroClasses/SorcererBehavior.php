<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Models\ItemBlueprint;

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
}
