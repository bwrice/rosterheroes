<?php


namespace App\Domain\Behaviors\Positions;


class GoalieBehavior extends PositionBehavior
{
    protected $positionValue = 50;
    protected $gamesPerSeason = 80;
    protected $abbreviation = 'G';
}
