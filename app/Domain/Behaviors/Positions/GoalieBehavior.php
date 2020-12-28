<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class GoalieBehavior extends PositionBehavior
{
    protected $positionValue = 40;
    protected $gamesPerSeason = 80;
    protected $significantAmountOfGamesWithoutStats = 5;
    protected $abbreviation = 'G';
    protected $factoryStatTypeNames = [
        StatType::HOCKEY_BLOCKED_SHOT,
        StatType::GOALIE_WIN,
        StatType::GOALIE_SAVE,
        StatType::GOAL_AGAINST
    ];
}
