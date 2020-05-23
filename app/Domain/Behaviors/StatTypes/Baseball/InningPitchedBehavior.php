<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class InningPitchedBehavior extends StatTypeBehavior
{
    protected $pointsPer = 3;
    protected $simpleName = 'Inning Pitched';
}
