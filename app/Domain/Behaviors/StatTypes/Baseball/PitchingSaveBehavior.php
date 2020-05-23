<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class PitchingSaveBehavior extends StatTypeBehavior
{
    protected $pointsPer = 3.5;
    protected $simpleName = 'Save';
}
