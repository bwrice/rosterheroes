<?php


namespace App\Domain\Behaviors\StatTypes\Football;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class RushTDBehavior extends StatTypeBehavior
{
    protected $pointsPer = 6;
    protected $simpleName = 'Rushing TD';
}
