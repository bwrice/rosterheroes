<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class WideReceiverBehavior extends PositionBehavior
{
    protected $positionValue = 45;
    protected $gamesPerSeason = 14;
    protected $significantAmountOfGamesWithoutStats = 4;
    protected $abbreviation = 'WR';
    protected $factoryStatTypeNames = [
        StatType::REC_TD,
        StatType::RECEPTION,
        StatType::REC_YARD
    ];
}
