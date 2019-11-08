<?php


namespace App\Domain\Behaviors\Realm\Territories;


class CrypticCoastBehavior extends TerritoryBehavior
{
    protected $realmColor = '#ba3f3f';
    protected $viewBox = [
        'pan_x' => 3,
        'pan_y' => 36,
        'zoom_x' => 100,
        'zoom_y' => 75
    ];
}
