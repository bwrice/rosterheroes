<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class StolenBaseBehavior extends StatTypeBehavior
{
    protected $pointsPer = 5;
    protected $simpleName = 'Stolen Base';
}
