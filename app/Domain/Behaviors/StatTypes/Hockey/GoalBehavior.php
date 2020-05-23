<?php


namespace App\Domain\Behaviors\StatTypes\Hockey;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class GoalBehavior extends StatTypeBehavior
{
    protected $pointsPer = 12;
    protected $simpleName = 'Goal';
}
