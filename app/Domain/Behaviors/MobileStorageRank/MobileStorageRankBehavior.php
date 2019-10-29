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

    /**
     * @return int
     */
    public function getWeightCapacity(): int
    {
        return $this->weightCapacity;
    }
}
