<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class CompleteGameBehavior extends StatTypeBehavior
{
    protected $pointsPer = 3;
    protected $simpleName = 'Complete Game';
}
