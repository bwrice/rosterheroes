<?php


namespace App\Domain\Behaviors\Realm\Territories;


class ArcticArchipelagoBehavior extends TerritoryBehavior
{
    protected $realmColor = '#586b6d';
    protected $viewBox = [
        'pan_x' => 24,
        'pan_y' => -28,
        'zoom_x' => 146,
        'zoom_y' => 111
    ];
}
