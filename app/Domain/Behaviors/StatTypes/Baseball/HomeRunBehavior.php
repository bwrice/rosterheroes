<?php


namespace App\Domain\Behaviors\StatTypes\Baseball;


use App\Domain\Behaviors\StatTypes\StatTypeBehavior;

class HomeRunBehavior extends StatTypeBehavior
{
    protected $pointsPer = 8;
    protected $simpleName = 'Home Run';
}
