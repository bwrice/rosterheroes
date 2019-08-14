<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/6/18
 * Time: 8:12 PM
 */

namespace App\Domain\Behaviors\HeroClasses;


use App\Domain\Models\ItemBlueprint;
use App\Domain\Collections\ItemBlueprintCollection;
use App\Domain\Models\Measurable;
use Illuminate\Support\Collection;

abstract class HeroClassBehavior
{
    /**
     * @return array
     */
    abstract protected function getStarterItemBlueprintNames(): array;

    abstract protected function getMeasurableStartingBonusAmount($measurableTypeName): int;

    abstract protected function getCostToRaiseCoefficient($measurableTypeName): int;

    abstract protected function getCostToRaiseExponent($measurableTypeName): float;

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
        $K = $this->getCostToRaiseCoefficient($measurable->measurableType->name);
        $n = $this->getCostToRaiseExponent($measurable->measurableType->name);
        $J = $measurable->getCostToRaiseCoefficientMultiplier();
        $x = $measurable->amount_raised;

        return ($J * $K * $x) + ($x**$n);
    }


    public function getCurrentMeasurableAmount(Measurable $measurable): int
    {
        $currentAmount = $measurable->measurableType->getBehavior()->getBaseAmount() + $measurable->amount_raised;
        $currentAmount += $this->getMeasurableStartingBonusAmount($measurable->measurableType->name);

        return $currentAmount;
    }
}
