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

class HeroClassBehavior
{
    /**
     * @var array
     */
    private $starterItemBlueprintNames;

    public function __construct(array $starterItemBlueprintNames)
    {
        $this->starterItemBlueprintNames = $starterItemBlueprintNames;
    }

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return $this->starterItemBlueprintNames;
    }

    /**
     * @return \App\Domain\Collections\ItemBlueprintCollection
     */
    public function getStartItemBlueprints()
    {
        /** @var ItemBlueprintCollection $collection */
        $collection = ItemBlueprint::query()->whereIn('item_name', $this->getStarterItemBlueprintNames())->get();
        return $collection;
    }
}