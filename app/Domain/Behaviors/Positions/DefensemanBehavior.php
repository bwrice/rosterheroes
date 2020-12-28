<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class DefensemanBehavior extends PositionBehavior
{
    protected $positionValue = 20;
    protected $gamesPerSeason = 80;
    protected $significantAmountOfGamesWithoutStats = 5;
    protected $abbreviation = 'D';
    protected $factoryStatTypeNames = [
        StatType::GOAL,
        StatType::HOCKEY_ASSIST,
        StatType::SHOT_ON_GOAL
    ];
}
