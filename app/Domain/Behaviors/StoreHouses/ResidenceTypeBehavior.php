<?php


namespace App\Domain\Behaviors\StoreHouses;


abstract class ResidenceTypeBehavior
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
