<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class PowerForwardBehavior extends PositionBehavior
{
    protected $positionValue = 60;
    protected $gamesPerSeason = 80;
    protected $significantAmountOfGamesWithoutStats = 5;
    protected $abbreviation = 'PF';
    protected $factoryStatTypeNames = [
        StatType::POINT_MADE,
        StatType::THREE_POINTER,
        StatType::REBOUND,
        StatType::BASKETBALL_ASSIST,
        StatType::STEAL,
        StatType::BASKETBALL_BLOCK,
        StatType::TURNOVER
    ];
}
