<?php


namespace App\Domain\Behaviors\Realm\Continents;


class WestWozulBehavior extends ContinentBehavior
{
    protected $realmColor = '#c12907';
    protected $viewBox = [
        'pan_x' => 135,
        'pan_y' => 99,
        'zoom_x' => 150,
        'zoom_y' => 114
    ];
    protected $minLevelRequirement = 25;
}
