<?php


namespace App\Domain\Behaviors\Realm\Territories;


class DesertOfDespairBehavior extends TerritoryBehavior
{
    protected $realmColor = '#99844a';
    protected $viewBox = [
        'pan_x' => 190,
        'pan_y' => 105,
        'zoom_x' => 75,
        'zoom_y' => 57
    ];
}
