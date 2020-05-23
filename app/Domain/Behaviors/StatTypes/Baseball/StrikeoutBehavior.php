<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class StrikeoutBehavior extends StatTypeBehavior
{
    protected $pointsPer = 2.5;
    protected $simpleName = 'Strikeout';
}
