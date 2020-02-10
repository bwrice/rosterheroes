<?php


namespace App\Domain\Behaviors\Positions;


class DefensemanBehavior extends PositionBehavior
{
    protected $positionValue = 30;
    protected $gamesPerSeason = 80;
    protected $abbreviation = 'D';
}
