<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class CompleteGameShutoutBehavior extends StatTypeBehavior
{
    protected $pointsPer = 5;
    protected $simpleName = 'Shutout';
}
