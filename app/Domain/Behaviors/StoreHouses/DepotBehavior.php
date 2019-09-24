<?php


namespace App\Domain\Behaviors\StoreHouses;


class DepotBehavior extends StoreHouseBehavior
{
    public function __construct()
    {
        $this->getTotalSlotsCount = 300;
    }
}
