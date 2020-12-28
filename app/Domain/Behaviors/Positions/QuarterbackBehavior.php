<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class QuarterbackBehavior extends PositionBehavior
{
    protected $positionValue = 60;
    protected $gamesPerSeason = 16;
    protected $significantAmountOfGamesWithoutStats = 3;
    protected $abbreviation = 'QB';
    protected $factoryStatTypeNames = [
        StatType::PASS_TD,
        StatType::PASS_YARD,
        StatType::INTERCEPTION
    ];
}
