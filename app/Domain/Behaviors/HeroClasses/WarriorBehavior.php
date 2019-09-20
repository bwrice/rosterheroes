<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Behaviors\HeroClasses\MeasurableOperators\HeroMeasurableCalculator;
use App\Domain\Behaviors\HeroClasses\MeasurableOperators\WarriorMeasurableOperator;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\ItemBlueprint;

class WarriorBehavior extends HeroClassBehavior
{
    public function __construct(
        HeroMeasurableCalculator $measurableCalculator,
        WarriorMeasurableOperator $measurableOperator)
    {
        parent::__construct($measurableCalculator, $measurableOperator);
    }

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_SHIELD,
            ItemBlueprint::STARTER_SWORD
        ];
    }

    public function getStartingCombatPosition(): CombatPosition
    {
        return CombatPosition::frontLine();
    }
}
