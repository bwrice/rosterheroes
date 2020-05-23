<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class BaseOnBallsBehavior extends StatTypeBehavior
{
    protected $pointsPer = 2;
    protected $simpleName = 'Base on Balls';
}
