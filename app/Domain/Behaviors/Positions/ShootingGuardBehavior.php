<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class ShootingGuardBehavior extends PositionBehavior
{
    protected $positionValue = 90;
    protected $gamesPerSeason = 80;
    protected $abbreviation = 'SG';
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
