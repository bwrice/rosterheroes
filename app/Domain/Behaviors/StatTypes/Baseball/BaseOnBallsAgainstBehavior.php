<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class BaseOnBallsAgainstBehavior extends StatTypeBehavior
{
    protected $pointsPer = -.75;
    protected $simpleName = 'Base on Balls Against';
}
