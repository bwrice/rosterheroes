<?php


namespace App\Domain\Behaviors\StatTypes\Football;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class ReceptionBehavior extends StatTypeBehavior
{
    protected $pointsPer = .5;
    protected $simpleName = 'Reception';
}
