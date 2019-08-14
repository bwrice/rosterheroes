<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Behaviors\HeroClasses\MeasurableOperators\HeroMeasurableCalculator;
use App\Domain\Behaviors\HeroClasses\MeasurableOperators\RangerMeasurableOperator;
use App\Domain\Models\ItemBlueprint;

class RangerBehavior extends HeroClassBehavior
{
    public function __construct(
        HeroMeasurableCalculator $measurableCalculator,
        RangerMeasurableOperator $measurableOperator)
    {
        parent::__construct($measurableCalculator, $measurableOperator);
    }

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_BOW
        ];
    }
}
