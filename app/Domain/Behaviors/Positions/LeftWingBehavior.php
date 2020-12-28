<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class LeftWingBehavior extends PositionBehavior
{
    protected $positionValue = 30;
    protected $gamesPerSeason = 80;
    protected $significantAmountOfGamesWithoutStats = 5;
    protected $abbreviation = 'LW';
    protected $factoryStatTypeNames = [
        StatType::GOAL,
        StatType::HOCKEY_ASSIST,
        StatType::SHOT_ON_GOAL
    ];
}
