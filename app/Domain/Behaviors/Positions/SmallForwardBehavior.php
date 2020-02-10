<?php


namespace App\Domain\Behaviors\Positions;


class SmallForwardBehavior extends PositionBehavior
{
    protected $positionValue = 90;
    protected $gamesPerSeason = 80;
    protected $abbreviation = 'SF';
}
