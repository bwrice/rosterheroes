<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class HitBatsmenBehavior extends StatTypeBehavior
{
    protected $pointsPer = -.75;
    protected $simpleName = 'Hit Batsman';
}
