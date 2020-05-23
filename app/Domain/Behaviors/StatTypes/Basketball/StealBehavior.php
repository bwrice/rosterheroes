<?php


namespace App\Domain\Behaviors\StatTypes\Basketball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class StealBehavior extends StatTypeBehavior
{
    protected $pointsPer = 1;
    protected $simpleName = 'Steal';
}
