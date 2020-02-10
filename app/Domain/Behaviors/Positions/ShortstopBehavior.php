<?php


namespace App\Domain\Behaviors\Positions;


class ShortstopBehavior extends PositionBehavior
{
    protected $positionValue = 50;
    protected $gamesPerSeason = 150;
    protected $abbreviation = 'SS';
}
