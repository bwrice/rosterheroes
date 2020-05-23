<?php


namespace App\Domain\Behaviors\StatTypes\Football;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class PassTDBehavior extends StatTypeBehavior
{
    protected $pointsPer = 4;
    protected $simpleName = 'Passing TD';
}
