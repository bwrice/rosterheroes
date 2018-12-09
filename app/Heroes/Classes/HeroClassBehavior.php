<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/6/18
 * Time: 8:12 PM
 */

namespace App\Heroes\Classes;


use App\ItemBlueprint;
use App\Items\Blueprints\ItemBlueprintCollection;
use Illuminate\Database\Eloquent\Collection;

abstract class HeroClassBehavior
{
    abstract protected function getStarterItemBlueprintNames(): array;

    /**
     * @return ItemBlueprintCollection
     */
    public function getStartItemBlueprints()
    {
        return ItemBlueprint::whereIn('item_name', $this->getStarterItemBlueprintNames())->get();
    }
}