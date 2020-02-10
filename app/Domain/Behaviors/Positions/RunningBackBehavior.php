<?php


namespace App\Domain\Behaviors\Positions;


class RunningBackBehavior extends PositionBehavior
{
    protected $positionValue = 50;
    protected $gamesPerSeason = 14;
    protected $abbreviation = 'RB';
}
