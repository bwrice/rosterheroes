<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Behaviors\HeroClasses\MeasurableOperators\HeroMeasurableCalculator;
use App\Domain\Behaviors\HeroClasses\MeasurableOperators\RangerMeasurableOperator;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\MeasurableType;

class RangerBehavior extends HeroClassBehavior
{
    protected $primaryMeasurableTypes = [
        MeasurableType::AGILITY,
        MeasurableType::FOCUS,
        MeasurableType::STAMINA
    ];

    protected $secondaryMeasurableTypes = [
        MeasurableType::STRENGTH,
        MeasurableType::MANA,
    ];

    protected $starterItemBlueprintNames = [
        ItemBlueprint::STARTER_BOW,
        ItemBlueprint::STARTER_LIGHT_ARMOR
    ];

    public function __construct()
    {
        $this->startingCombatPosition = CombatPosition::backLine();
    }

    public function getIconSVG(): string
    {
        return "<svg viewBox=\"0 0 38.4 48\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\" style=\"display: block\">
                    <title>Ranger Icon</title>
                    <path d=\"M4.51983 11.1074C7.43451 14.5197 11.6067 19.5097 14 17.3482C15.6 15.7482 18.8333 15.3482 20 15.3482C17.2738 17.9141 12.017 22.9488 11.9798 23.3258C12.0319 23.3417 12.0404 23.3482 12 23.3482C11.9849 23.3482 11.9783 23.3406 11.9798 23.3258C11.3864 23.1445 5.13682 21.743 1 14.8483C-9.50002 -0.65168 -26.5 -9.15175 -44 -9.65181C-37.5 -12.6517 -12.983 -12.5284 4.51983 11.1074Z\" fill=\"#474747\"/>
                    <path d=\"M30.2407 36.4802C26.8284 33.5655 21.8384 29.3933 23.9999 27C25.5999 25.4 25.9999 22.1667 25.9999 21C23.4341 23.7262 18.3994 28.983 18.0224 29.0202C18.0065 28.9681 17.9999 28.9596 17.9999 29C17.9999 29.0151 18.0076 29.0217 18.0224 29.0202C18.2037 29.6136 19.6052 35.8632 26.4999 40C41.9998 50.5 50.4999 67.5 51 85C53.9999 78.5 53.8766 53.983 30.2407 36.4802Z\" fill=\"#474747\"/>
                    <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M30.806 17.5842L38.799 2.24268L23.4575 10.2357L25.78 12.5582L-61 99.3382L-58.1716 102.167L28.6084 15.3866L30.806 17.5842Z\" fill=\"#006591\"/>
                </svg>";
    }
}
