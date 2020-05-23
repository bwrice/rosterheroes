<?php


namespace App\Domain\Behaviors\StatTypes\Hockey;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class ShotOnGoalBehavior extends StatTypeBehavior
{
    protected $pointsPer = 3;
    protected $simpleName = 'Shot on Goal';
}
