<?php


namespace App\Domain\Behaviors\StatTypes\Hockey;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class GoalieWinBehavior extends StatTypeBehavior
{
    protected $pointsPer = 7;
    protected $simpleName = 'Win';
}
