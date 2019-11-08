<?php


namespace App\Domain\Behaviors\Realm\Territories;


class WoodsOfTheWildBehavior extends TerritoryBehavior
{

    protected $realmColor = '#579368';
    protected $viewBox = [
        'pan_x' => 204,
        'pan_y' => 24,
        'zoom_x' => 80,
        'zoom_y' => 60
    ];
}
