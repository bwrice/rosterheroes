<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class OutfieldBehavior extends PositionBehavior
{
    protected $positionValue = 50;
    protected $gamesPerSeason = 150;
    protected $significantAmountOfGamesWithoutStats = 6;
    protected $abbreviation = 'OF';
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
