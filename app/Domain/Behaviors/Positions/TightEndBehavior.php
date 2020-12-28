<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class TightEndBehavior extends PositionBehavior
{
    protected $positionValue = 30;
    protected $gamesPerSeason = 14;
    protected $significantAmountOfGamesWithoutStats = 4;
    protected $abbreviation = 'TE';
    protected $factoryStatTypeNames = [
        StatType::REC_TD,
        StatType::RECEPTION,
        StatType::REC_YARD
    ];
}
