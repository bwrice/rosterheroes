<?php


namespace App\Domain\Behaviors\Realm\Continents;


class DemauxorBehavior extends ContinentBehavior
{
    protected $realmColor = '#9e1284';
    protected $viewBox = [
        'pan_x' => 0,
        'pan_y' => 126,
        'zoom_x' => 160,
        'zoom_y' => 121
    ];
    protected $minLevelRequirement = 40;
}
