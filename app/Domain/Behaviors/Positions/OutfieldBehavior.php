<?php


namespace App\Domain\Behaviors\Positions;


class OutfieldBehavior extends PositionBehavior
{
    protected $positionValue = 60;
    protected $gamesPerSeason = 150;
    protected $abbreviation = 'OF';
}
