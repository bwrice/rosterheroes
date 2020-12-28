<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class PitcherBehavior extends PositionBehavior
{
    protected $positionValue = 50;
    protected $gamesPerSeason = 30;
    protected $significantAmountOfGamesWithoutStats = 12;
    protected $abbreviation = 'P';
    protected $factoryStatTypeNames = [
        StatType::INNING_PITCHED,
        StatType::STRIKEOUT,
        StatType::PITCHING_WIN,
        StatType::PITCHING_SAVE,
        StatType::EARNED_RUN_ALLOWED,
        StatType::HIT_AGAINST,
        StatType::BASE_ON_BALLS_AGAINST,
        StatType::HIT_BATSMAN,
        StatType::COMPLETE_GAME,
        StatType::COMPLETE_GAME_SHUTOUT
    ];
}
