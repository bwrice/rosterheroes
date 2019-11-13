<?php


namespace App\Domain\Behaviors\Realm\Continents;


class VindoberonBehavior extends ContinentBehavior
{
    protected $realmColor = '#4f547a';
    protected $viewBox = [
        'pan_x' => -48,
        'pan_y' => 8,
        'zoom_x' => 184,
        'zoom_y' => 141
    ];
    protected $minLevelRequirement = 35;
}
