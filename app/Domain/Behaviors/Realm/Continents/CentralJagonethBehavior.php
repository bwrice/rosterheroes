<?php


namespace App\Domain\Behaviors\Realm\Continents;


class CentralJagonethBehavior extends ContinentBehavior
{
    protected $realmColor = '#3e81a5';
    protected $viewBox = [
        'pan_x' => 48,
        'pan_y' => 48,
        'zoom_x' => 130,
        'zoom_y' => 99
    ];
}
