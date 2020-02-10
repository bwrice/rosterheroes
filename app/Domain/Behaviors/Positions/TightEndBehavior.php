<?php


namespace App\Domain\Behaviors\Positions;


use App\Domain\Models\StatType;

class TightEndBehavior extends PositionBehavior
{
    protected $positionValue = 35;
    protected $gamesPerSeason = 14;
    protected $abbreviation = 'TE';
    protected $factoryStatTypeNames = [
        StatType::REC_TD,
        StatType::RECEPTION,
        StatType::REC_YARD
    ];
}
