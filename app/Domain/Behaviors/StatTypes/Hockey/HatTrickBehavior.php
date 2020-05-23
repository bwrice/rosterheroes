<?php


namespace App\Domain\Behaviors\StatTypes\Hockey;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class HatTrickBehavior extends StatTypeBehavior
{
    protected $pointsPer = 5;
    protected $simpleName = 'Hat Trick';
}
