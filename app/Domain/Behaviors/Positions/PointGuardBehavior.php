<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class PointGuardBehavior extends PositionBehavior
{
    protected $positionValue = 70;
    protected $gamesPerSeason = 80;
    protected $significantAmountOfGamesWithoutStats = 5;
    protected $abbreviation = 'PG';
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
