<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/6/18
 * Time: 8:12 PM
 */

namespace App\Domain\Behaviors\HeroClasses;


use App\Domain\Interfaces\MeasurableCalculator;
use App\Domain\Interfaces\MeasurableOperator;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Collections\ItemBlueprintCollection;
use App\Domain\Models\Measurable;
use Illuminate\Support\Collection;

abstract class HeroClassBehavior
{
    /**
     * @var MeasurableCalculator
     */
    private $measurableCalculator;

    public function __construct(MeasurableCalculator $measurableOperator)
    {
        $this->measurableCalculator = $measurableOperator;
    }

    /**
     * @return array
     */
    abstract protected function getStarterItemBlueprintNames(): array;

    abstract protected function getMeasurableStartingBonusAmount($measurableTypeName): int;

    abstract protected function getCostToRaiseCoefficient($measurableTypeName): int;

    abstract protected function getCostToRaiseExponent($measurableTypeName): float;

    abstract protected function getMeasurableOperator(): MeasurableOperator;

    /**
     * @return ItemBlueprintCollection
     */
    public function getStartItemBlueprints()
    {
        /** @var ItemBlueprintCollection $collection */
        $collection = ItemBlueprint::query()->whereIn('item_name', $this->getStarterItemBlueprintNames())->get();
        return $collection;
    }

    /**
     * @param Measurable $measurable
     * @return int
     */
    public function costToRaiseMeasurable(Measurable $measurable): int
    {
        return $this->measurableCalculator->getCostToRaiseMeasurable($measurable, $this->getMeasurableOperator());
    }

    /**
     * @param Measurable $measurable
     * @return int
     */
    public function getCurrentMeasurableAmount(Measurable $measurable): int
    {
        return $this->measurableCalculator->getCurrentMeasurableAmount($measurable, $this->getMeasurableOperator());
    }
}
