<?php


namespace App\Domain\Behaviors\Positions;


class PitcherBehavior extends PositionBehavior
{
    protected $positionValue = 70;
    protected $gamesPerSeason = 30;
    protected $abbreviation = 'P';
}
