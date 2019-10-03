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

    public function getIconSVG(): string
    {
        return "<svg viewBox=\"0 0 38.4 48\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\" style=\"display: block\">
                    <title>Sorcerer Icon</title>
                    <circle cx=\"12.9126\" cy=\"12.9126\" r=\"12.9126\" fill=\"#FFB21D\"/>
                    <path d=\"M18.7383 11.1666C20.9473 13.1283 23.7417 17.8103 19.898 22.9369C18.3495 24.2961 15.9708 25.4854 10.364 25.1456C8.32069 24.5789 3.46293 22.7538 0.679565 17.3133C0.679565 19.9289 2.51739 24.4517 6.62617 26.3349C8.4951 27.1915 9.85432 26.8446 15.631 31.432C19.898 39.0776 18.7383 36.8689 22.2572 40.267L29.5631 47.5728H35L31.6019 44.3446L26.5048 38.7378C24.351 37.1685 19.5336 30.156 22.7145 22.9369C23.5429 21.847 25.3322 18.9873 25.8624 16.2671C26.6747 7.64561 25.4854 9.51454 19.898 3.05823C21.7205 7.41758 24.04 15.1424 18.7383 11.1666Z\" fill=\"#474747\"/>
                </svg>";
    }
}
