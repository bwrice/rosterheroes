<?php


namespace App\Domain\Behaviors\StoreHouses;


class ShackBehavior extends HouseTypeBehavior
{
    public function __construct()
    {
        $this->getTotalSlotsCount = 300;
    }
}
