<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Behaviors\HeroClasses\MeasurableOperators\HeroMeasurableCalculator;
use App\Domain\Behaviors\HeroClasses\MeasurableOperators\RangerMeasurableOperator;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\ItemBlueprint;

class RangerBehavior extends HeroClassBehavior
{
    public function __construct(
        HeroMeasurableCalculator $measurableCalculator,
        RangerMeasurableOperator $measurableOperator)
    {
        parent::__construct($measurableCalculator, $measurableOperator);
    }

    public function getIconSrc(): string
    {
        return asset('svg/icons/heroClasses/ranger.svg');
    }

    public function getIconAlt(): string
    {
        return 'ranger hero class';
    }

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_BOW,
            ItemBlueprint::STARTER_LIGHT_ARMOR
        ];
    }

    public function getStartingCombatPosition(): CombatPosition
    {
        return CombatPosition::backLine();
    }
}
