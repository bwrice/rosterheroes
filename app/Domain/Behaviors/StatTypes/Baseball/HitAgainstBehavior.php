<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class HitAgainstBehavior extends StatTypeBehavior
{
    protected $pointsPer = -.75;
    protected $simpleName = 'Hit Against';
}
