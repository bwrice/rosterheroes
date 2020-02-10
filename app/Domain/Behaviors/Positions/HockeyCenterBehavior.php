<?php


namespace App\Domain\Behaviors\Positions;


class HockeyCenterBehavior extends PositionBehavior
{
    protected $positionValue = 40;
    protected $gamesPerSeason = 80;
    protected $abbreviation = 'C';
}
