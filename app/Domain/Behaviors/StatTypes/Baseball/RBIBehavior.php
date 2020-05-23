<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class RBIBehavior extends StatTypeBehavior
{
    protected $pointsPer = 3;
    protected $simpleName = 'Run Batted In';
}
