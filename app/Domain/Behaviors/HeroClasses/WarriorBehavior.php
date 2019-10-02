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

    public function getIconAlt(): string
    {
        return 'warrior hero class';
    }

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames(): array
    {
        return [
            ItemBlueprint::STARTER_SHIELD,
            ItemBlueprint::STARTER_SWORD,
            ItemBlueprint::STARTER_HEAVY_ARMOR
        ];
    }

    public function getStartingCombatPosition(): CombatPosition
    {
        return CombatPosition::frontLine();
    }

    public function getIconSrc(): string
    {
        return asset('svg/icons/heroClasses/warrior.svg');
    }
}
