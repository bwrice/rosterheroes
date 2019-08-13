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
use App\Domain\Models\Measurable;

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

    public function costToRaiseMeasurable(Measurable $measurable): int
    {
        // TODO
        return 10;
    }

    public function getCurrentMeasurableAmount(Measurable $measurable): int
    {
        return 50;
    }
}
