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
    /**
     * @var MeasurableOperator
     */
    private $measurableOperator;

    public function __construct(MeasurableCalculator $measurableCalculator, MeasurableOperator $measurableOperator)
    {
        $this->measurableCalculator = $measurableCalculator;
        $this->measurableOperator = $measurableOperator;
    }

    /**
     * @return array
     */
    abstract protected function getStarterItemBlueprintNames(): array;

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
        return $this->measurableCalculator->getCostToRaiseMeasurable($measurable, $this->measurableOperator);
    }

    /**
     * @param Measurable $measurable
     * @return int
     */
    public function getCurrentMeasurableAmount(Measurable $measurable): int
    {
        return $this->measurableCalculator->getCurrentMeasurableAmount($measurable, $this->measurableOperator);
    }
}
