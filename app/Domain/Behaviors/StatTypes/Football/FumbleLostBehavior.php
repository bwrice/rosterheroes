<?php


namespace App\Domain\Behaviors\StatTypes\Football;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class FumbleLostBehavior extends StatTypeBehavior
{
    protected $pointsPer = -2;
}
