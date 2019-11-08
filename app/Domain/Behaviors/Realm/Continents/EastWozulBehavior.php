<?php


namespace App\Domain\Behaviors\Realm\Continents;


class EastWozulBehavior extends ContinentBehavior
{
    protected $realmColor = '#d18c02';
    protected $viewBox = [
        'pan_x' => 185,
        'pan_y' => 70,
        'zoom_x' => 130,
        'zoom_y' => 99
    ];
}
