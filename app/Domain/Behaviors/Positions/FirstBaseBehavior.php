<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class FirstBaseBehavior extends PositionBehavior
{
    protected $positionValue = 40;
    protected $gamesPerSeason = 150;
    protected $significantAmountOfGamesWithoutStats = 6;
    protected $abbreviation = '1B';
    protected $factoryStatTypeNames = [
        StatType::HIT,
        StatType::DOUBLE,
        StatType::TRIPLE,
        StatType::HOME_RUN,
        StatType::RUN_BATTED_IN,
        StatType::RUN_SCORED,
        StatType::BASE_ON_BALLS,
        StatType::HIT_BY_PITCH,
        StatType::STOLEN_BASE
    ];
}
