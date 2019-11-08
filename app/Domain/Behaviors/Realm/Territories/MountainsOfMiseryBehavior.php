<?php


namespace App\Domain\Behaviors\Realm\Territories;


class MountainsOfMiseryBehavior extends TerritoryBehavior
{
    protected $realmColor = '#6c6491';
    protected $viewBox = [
        'pan_x' => -40,
        'pan_y' => 44,
        'zoom_x' => 130,
        'zoom_y' => 99
    ];
}
