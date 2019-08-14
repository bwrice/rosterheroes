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
use Illuminate\Support\Collection;

class HeroClassBehavior
{

    /**
     * @var array
     */
    private $starterItemBlueprintNames;
    /**
     * @var Collection
     */
    private $measurableAmountBonuses;

    public function __construct(Collection $measurableAmountBonuses, array $starterItemBlueprintNames)
    {
        $this->measurableAmountBonuses = $measurableAmountBonuses;
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
     * @return ItemBlueprintCollection
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
        $currentAmount = $measurable->measurableType->getBehavior()->getBaseAmount() + $measurable->amount_raised;
        $measurableTypeName = $measurable->measurableType->name;

        $this->measurableAmountBonuses->each(function($bonus) use (&$currentAmount, $measurableTypeName) {
            if ($measurableTypeName === $bonus['measurable_type']) {
                $currentAmount += $bonus['amount'];
            }
        });

        return $currentAmount;
    }
}
