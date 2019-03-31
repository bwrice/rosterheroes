<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/6/18
 * Time: 8:12 PM
 */

namespace App\Domain\Behaviors\HeroClass;


use App\Domain\Models\ItemBlueprint;
use App\Domain\Collections\ItemBlueprintCollection;
use Illuminate\Database\Eloquent\Collection;

abstract class HeroClassBehavior
{
    abstract protected function getStarterItemBlueprintNames(): array;

    /**
     * @return \App\Domain\Collections\ItemBlueprintCollection
     */
    public function getStartItemBlueprints()
    {
        return ItemBlueprint::whereIn('item_name', $this->getStarterItemBlueprintNames())->get();
    }
}