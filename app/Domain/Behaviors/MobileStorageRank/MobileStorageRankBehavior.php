<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/3/19
 * Time: 10:07 PM
 */

namespace App\Domain\Behaviors\MobileStorageRank;


abstract class MobileStorageRankBehavior
{
    protected $weightCapacity;

    abstract public function getSlotsCount();

    /**
     * @return int
     */
    public function getWeightCapacity(): int
    {
        return $this->weightCapacity;
    }
}
