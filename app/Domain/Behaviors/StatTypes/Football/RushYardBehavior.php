<?php


namespace App\Domain\Behaviors\StatTypes\Football;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class RushYardBehavior extends StatTypeBehavior
{
    protected $pointsPer = .1;
    protected $simpleName = 'Rushing Yard';
}
