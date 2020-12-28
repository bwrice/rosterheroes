<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class RunningBackBehavior extends PositionBehavior
{
    protected $positionValue = 45;
    protected $gamesPerSeason = 14;
    protected $significantAmountOfGamesWithoutStats = 4;
    protected $abbreviation = 'RB';
    protected $factoryStatTypeNames = [
        StatType::RUSH_TD,
        StatType::RUSH_YARD,
        StatType::RECEPTION,
        StatType::REC_YARD
    ];
}
