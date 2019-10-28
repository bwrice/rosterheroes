<?php


namespace App\Domain\Behaviors\StoreHouses;


abstract class ResidenceTypeBehavior
{
    protected $maxItemCount = 0;

    /**
     * @return int
     */
    public function getMaxItemCount(): int
    {
        return $this->maxItemCount;
    }

}
