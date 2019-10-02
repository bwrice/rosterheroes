<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Behaviors\HeroClasses\MeasurableOperators\HeroMeasurableCalculator;
use App\Domain\Behaviors\HeroClasses\MeasurableOperators\SorcererMeasurableOperator;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\ItemBlueprint;

class SorcererBehavior extends HeroClassBehavior
{
    public function __construct(
        HeroMeasurableCalculator $measurableCalculator,
        SorcererMeasurableOperator $measurableOperator)
    {
        parent::__construct($measurableCalculator, $measurableOperator);
    }

    public function getIconAlt(): string
    {
        return 'sorcerer hero class';
    }

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_STAFF,
            ItemBlueprint::STARTER_ROBES
        ];
    }

    public function getStartingCombatPosition(): CombatPosition
    {
        return CombatPosition::backLine();
    }

    public function getIconSrc(): string
    {
        return asset('svg/icons/heroClasses/sorcerer.svg');
    }
}
