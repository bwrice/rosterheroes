<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class HockeyCenterBehavior extends PositionBehavior
{
    protected $positionValue = 40;
    protected $gamesPerSeason = 80;
    protected $abbreviation = 'C';
    protected $factoryStatTypeNames = [
        StatType::GOAL,
        StatType::HOCKEY_ASSIST,
        StatType::SHOT_ON_GOAL
    ];
}
