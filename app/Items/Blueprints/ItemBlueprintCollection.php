<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/7/18
 * Time: 2:17 PM
 */

namespace App\Items\Blueprints;

use App\ItemBlueprint;
use App\Items\ItemCollection;
use Illuminate\Database\Eloquent\Collection;

class ItemBlueprintCollection extends Collection
{
    /**
     * @return ItemCollection
     */
    public function generate()
    {
        $itemCollection = new ItemCollection();
        $this->each(function (ItemBlueprint $blueprint) use (&$itemCollection) {
            $itemCollection->push($blueprint->generate());
        });
        return $itemCollection;
    }
}