<?php


namespace App\Domain\Behaviors\StoreHouses;


abstract class HouseTypeBehavior
{
    protected $getTotalSlotsCount = 0;

    /**
     * @return int
     */
    public function getGetTotalSlotsCount(): int
    {
        return $this->getTotalSlotsCount;
    }
}
