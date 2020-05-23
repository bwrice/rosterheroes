<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class PitchingWinBehavior extends StatTypeBehavior
{
    protected $pointsPer = 6;
    protected $simpleName = 'Win';
}
