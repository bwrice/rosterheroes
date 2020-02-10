<?php


namespace App\Domain\Behaviors\Positions;


class QuarterbackBehavior extends PositionBehavior
{
    protected $positionValue = 75;
    protected $gamesPerSeason = 16;
    protected $abbreviation = 'QB';
}
