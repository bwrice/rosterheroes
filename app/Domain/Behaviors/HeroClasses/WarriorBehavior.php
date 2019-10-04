<?php


namespace App\Domain\Behaviors\HeroClasses;

use App\Domain\Behaviors\HeroClasses\MeasurableOperators\HeroMeasurableCalculator;
use App\Domain\Behaviors\HeroClasses\MeasurableOperators\WarriorMeasurableOperator;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\MeasurableType;

class WarriorBehavior extends HeroClassBehavior
{
    protected $primaryMeasurableTypes = [
        MeasurableType::STRENGTH,
        MeasurableType::VALOR,
        MeasurableType::HEALTH
    ];

    protected $secondaryMeasurableTypes = [
        MeasurableType::AGILITY,
        MeasurableType::STAMINA,
    ];

    protected $starterItemBlueprintNames = [
        ItemBlueprint::STARTER_SHIELD,
        ItemBlueprint::STARTER_SWORD,
        ItemBlueprint::STARTER_HEAVY_ARMOR
    ];

    public function __construct(
        HeroMeasurableCalculator $measurableCalculator,
        WarriorMeasurableOperator $measurableOperator)
    {
        parent::__construct($measurableCalculator, $measurableOperator);
        $this->startingCombatPosition = CombatPosition::frontLine();
    }

    public function getIconSVG(): string
    {
        return "<svg viewBox=\"0 0 38.4 48\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\" style=\"display: block\">
                    <title>Warrior Icon</title>
                    <path d=\"M21.053 1C16.4638 4.41434 10.039 13.9965 21.053 25.0105L21.053 33.271C20.1719 34.8129 14.9953 35.3636 18.4096 51.7745C15.1789 50.1591 7.94634 46.2675 2.99005 35.3636C-1.96624 24.4598 0.875361 9.19441 21.053 1Z\" fill=\"#A01E1E\"/>
                    <path d=\"M27.9918 1C32.581 4.41434 39.0058 13.9965 27.9918 25.0105L27.9918 33.271C28.8729 34.8129 34.0495 35.3636 30.6352 51.7745C33.8659 50.1591 41.0985 46.2675 46.0548 35.3636C51.011 24.4598 48.1694 9.19441 27.9918 1Z\" fill=\"#A01E1E\"/>
                    <rect x=\"22.0442\" y=\"23.028\" width=\"4.84615\" height=\"40.972\" fill=\"#474747\"/>
                    <circle cx=\"24.4673\" cy=\"19.2832\" r=\"2.42308\" fill=\"#474747\"/>
                </svg>";
    }
}
