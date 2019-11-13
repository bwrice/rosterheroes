<?php


namespace App\Domain\Behaviors\Realm\Continents;


class NorthJagonethBehavior extends ContinentBehavior
{
    protected $realmColor = '#46a040';
    protected $viewBox = [
        'pan_x' => 60,
        'pan_y' => 3,
        'zoom_x' => 160,
        'zoom_y' => 122
    ];
    protected $minLevelRequirement = 10;
}
