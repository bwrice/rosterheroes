<?php


namespace App\Domain\Behaviors\Realm\Continents;


class FetroyaBehavior extends ContinentBehavior
{
    protected $realmColor = '#b2b800';
    protected $viewBox = [
        'pan_x' => 178,
        'pan_y' => 18,
        'zoom_x' => 130,
        'zoom_y' => 99
    ];

    protected $minLevelRequirement = 0;
}
